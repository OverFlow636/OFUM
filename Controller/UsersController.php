<?php

App::uses('OfumAppController', 'Ofum.Controller');
class UsersController extends OfumAppController
{
	public $scaffold = 'admin';

    public function beforeFilter()
	{
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout', 'register', 'passwordReset');
    }

	public function admin_login()
	{
		$this->login();
		$this->render('admin_login');
	}

	public function login()
	{
		if ($this->request->isPost())
		{
			if ($this->Auth->login())
			{
				$this->User->id = $this->Auth->user('id');
				if (Configure::read('Ofum.trackLastLogin'))
					$this->User->saveField('last_login', date('Y-m-d H:i:s'));

				if (Configure::read('Ofum.trackLastAction'))
					$this->User->saveField('last_action', date('Y-m-d H:i:s'));

				$this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Invalid email address or password, try again'), 'notices/error');
			}
		}
	}

	public function logout()
	{
		$this->redirect($this->Auth->logout());
	}



	//crud functions

	public function index()
	{
		$this->set('users', $this->paginate());
	}

	public function register()
	{
		if ($this->request->isPost())
		{
			if (isset($this->request->data['first_name']))
			{
				$this->request->data['User']['group_id'] = 1;

				$this->fire('Plugin.Ofum.register_beforeValidate');
				if ($this->User->saveAll($this->request->data, array('validate'=>'only')))
				{
					$this->fire('Plugin.Ofum.register_afterValidate');

					$this->fire('Plugin.Ofum.register_beforeSaveAll');
					$this->User->saveAll($this->request->data);
					$this->request->data['User']['id'] = $this->User->getLastInsertId();
					$this->fire('Plugin.Ofum.register_afterSaveAll');

					$this->redirect(Configure::read('Ofum.registerRedirect'));
				}
			}
			elseif (isset($this->request->data['User']['email']))
			{
				if (isset($this->request->data['User']['ssid']) || isset($this->request->data['User']['pid']) || isset($this->request->data['User']['dob']))
				{
					$exist = $this->User->findByEmail($this->request->data['User']['email']);

					$reset = false;
					if (!empty($this->request->data['User']['ssid']))
						$reset = $this->request->data['User']['ssid'] == $exist['User']['ssid'];

					if (!$reset && !empty($this->request->data['User']['pid']))
						$reset = $this->request->data['User']['pid'] == $exist['User']['pid'];

					if (!$reset && !empty($this->request->data['User']['dob']))
						$reset = $this->request->data['User']['dob']['year'].'-'.$this->request->data['User']['dob']['month'].'-'.$this->request->data['User']['dob']['day'] == $exist['User']['dob'];


					if ($reset)
						$this->redirect(array('action'=>'passwordReset', $exist['User']['uuid']));
					else
					{
						$this->Session->setFlash('Sorry, the details you entered didnt match thoes on file.<br /> An email with a password reset link has been sent to your email address.', 'notices/notice');
						$args = array(
							'email_template_id'	=> 11,
							'sendTo'			=> $exist['User']['email'],
							'from'				=> array('noreply@alerrt.org')
						);
						$result = $this->_sendTemplateEmail($args, $exist);

						$this->redirect(array('action'=>'login'));
					}
				}
				else
				{
					//make sure email address is not already registered, if so forward to password reset screen
					$exist = $this->User->findByEmail($this->request->data['User']['email']);
					if ($exist)
					{
						$ssid = $pid = $dob = false;

						if (!empty($exist['User']['ssid']))
							$ssid = true;

						if (!empty($exist['User']['pid']))
							$pid = true;

						if (!empty($exist['User']['dob']))
							$dob = true;

						if ($ssid || $dob || $pid)
						{
							$this->set('ssid', $ssid);
							$this->set('pid', $pid);
							$this->set('dob', $dob);

							$this->set('email', $this->request->data['User']['email']);
							$this->Session->setFlash('Your email address is in use, reset your password below.', 'notices/notice');
							$this->render('account_verify');
						}
						else
						{
							$this->Session->setFlash('Sorry, no details on your account can be used for verification. An email with a password reset link has been sent to your email address.', 'notices/notice');
							$args = array(
								'email_template_id'	=> 11,
								'sendTo'			=> $exist['User']['email'],
								'from'				=> array('noreply@alerrt.org')
							);
							$result = $this->_sendTemplateEmail($args, $exist);
						}
					}
				}
			}
		}

	}

	public function passwordReset($uuid)
	{
		if ($this->request->is('post') || $this->request->is('put'))
		{
			$exist = $this->User->findByUuid($uuid);
			$this->request->data['User']['id'] = $exist['User']['id'];

			if ($this->User->save($this->request->data))
			{
				$this->Auth->login($exist['User']);
				$this->Session->setFlash('You have successfully changed your password and have been logged in.', 'notices/success');
				$this->redirect('/');
			}
		}

		$this->set('uuid', $uuid);
	}

	public function view($id = null, $renderView = null)
	{
		if ($id == null || $id != $this->Auth->user('id'))
			$id = $this->Auth->user('id');

		$this->fire('Plugin.Ofum.view_beforeRead');

		$this->set('user', $this->User->read(null, $id));

		if ($renderView)
		{
			$renderView=strtolower($renderView);
			$this->set('renderView', $renderView);
			$this->render('Users'.DS.'pages'.DS.$renderView);
		}
	}

	//admin sections

	public function admin_index()
	{
		$this->paginate = array(
			'limit'=>10
		);
		$this->set('users', $this->paginate());
	}

	public function admin_view($id = null)
	{
		$this->User->id = $id;
		if (!$this->User->exists())
			throw new NotFoundException(__('Invalid user'));

		$this->fire('Plugin.Ofum.admin_view_beforeRead');
		$this->set('user', $this->User->read(null, $id));
	}

	public function admin_dataTable($type='admin_index')
	{
		$this->datatable($type);
	}
	public function dataTable($type='index')
	{
		$conditions = array();
		switch($type)
		{
			case 'upcoming':
				$conditions[] = 'Course.startdate > NOW()';
				$conditions[] = array('Course.conference_id'=>0);
			break;
		}

		$order = array(
			'User.created'
		);

		if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1')
		{
			$limit = $_GET['iDisplayLength'];
			$offset = $_GET['iDisplayStart'];
		}
		else
		{
			$limit = 10;
			$offset = 0;
		}

		if (isset($_GET['iSortCol_0']))
		{
			switch ($type)
			{
				case 'upcoming':
					switch($_GET['iSortCol_0'])
					{
						case 0: $order = array('Course.startdate'=>$_GET['sSortDir_0']); break;
						case 1: $order = array('Course.course_type_id'=>$_GET['sSortDir_0']); break;
						case 2: $order = array('Course.location_description'=>$_GET['sSortDir_0']); break;
						case 3: $order = array('Course.status_id'=>$_GET['sSortDir_0']); break;
					}
				break;

				case 'admin_index':
					switch($_GET['iSortCol_0'])
					{
						case 0: $order = array('User.last_name'=>$_GET['sSortDir_0']); break;
						case 1: $order = array('Agency.name'=>$_GET['sSortDir_0']); break;
						case 2: $order = array('User.last_login'=>$_GET['sSortDir_0']); break;
						case 3: $order = array('User.created'=>$_GET['sSortDir_0']); break;
					}
				break;
			}

		}


		if (!empty($_GET['sSearch']))
		{
			$or = array();
			$or[] = array('User.first_name LIKE'=>$_GET['sSearch'].'%');
			$or[] = array('User.last_name LIKE'=>$_GET['sSearch'].'%');
			$or[] = array('User.email LIKE'=>$_GET['sSearch'].'%');
			$or[] = array('User.pid LIKE'=>$_GET['sSearch'].'%');
			$or[] = array('User.ssid LIKE'=>$_GET['sSearch'].'%');
			$or[] = array('Agency.name LIKE'=>$_GET['sSearch'].'%');
			$conditions[] = array('or'=>$or);
		}

		$this->User->recursive = 1;
		$this->User->contain(array(
			'Agency'
		));
		$found = $this->User->find('count', array(
			'conditions'=>$conditions
		));
		$this->User->contain(array(
			'Agency'
		));
		$courses = $this->User->find('all', array(
			'conditions'=>$conditions,
			'order'=>$order,
			'limit'=>$limit,
			'offset'=>$offset
		));

		//echo "/* ".print_r($order, true).' */';

		$this->set('found', $found);
		$this->set('users', $courses);
		$this->render('Users/tables'.DS.$type);
	}


	//instructor sections
	public function instructor_view($id = null)
	{
		if ($id == null)
			$id = $this->Auth->user('id');
		else
		{
			//make sure the logged user can edit the requested id
			switch($this->Auth->user('group_id'))
			{
				case 1:
				case 2:
				case 3:
				case 7:
				case 8:
					$this->Session->setFlash('You do not have permission to view this instructor.');
					$this->redirect(array('action'=>'view'));
			}
		}

		$this->User->contain(array(
			'Agency',
			'Instructor.Tier'
		));
		$currentUser = $this->User->read(null, $id);


		$this->loadModel('Ofcm.Instructing');
		$this->Instructing->contain(array('Course.CourseType', 'Course.Hosting', 'Course.Contact', 'Status'));
		$currentUser['Instructing']['pending'] = $this->Instructing->find('all', array(
			'conditions'=>array(
				'user_id'=>$id,
				'Instructing.status_id'=>1,
				'Course.enddate > now()',
				'Course.iclosed'=>false
			),
			'order'=>array(
				'Course.startdate'
			)
		),false);

		$this->Instructing->contain(array('Course.CourseType', 'Course.Hosting', 'Course.Contact', 'Status'));
		$currentUser['Instructing']['approved'] = $this->Instructing->find('all', array(
			'conditions'=>array(
				'user_id'=>$id,
				'Instructing.status_id'=>3,
				'Course.enddate > now()',
			),
			'order'=>array(
				'Course.startdate'
			)
		),false);


		$this->set('currentUser', $currentUser);
	}

}