<?php

App::uses('OfumAppController', 'Ofum.Controller');
class UsersController extends OfumAppController
{
	public $scaffold = 'admin';

    public function beforeFilter()
	{
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout', 'register');
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
				$this->Session->setFlash(__('Invalid username or password, try again'));
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
			$this->render('pages'.DIRECTORY_SEPARATOR.$renderView);
		}
	}






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

		$this->set('user', $this->User->read(null, $id));
	}

	public function admin_view_pr($id = null)
	{
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
			//$or[] = array('Course.location_description LIKE'=>$_GET['sSearch'].'%');
			//$or[] = array('CourseType.shortname LIKE'=>$_GET['sSearch'].'%');
			//$or[] = array('DATE_FORMAT(Course.startdate, "%M") LIKE'=>$_GET['sSearch'].'%');
			//$or[] = array('Course.id'=>$_GET['sSearch']);

			//$conditions[] = array('or'=>$or);
		}

		$this->User->recursive = 1;
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
		$this->render('tables'.DS.$type);
	}
}