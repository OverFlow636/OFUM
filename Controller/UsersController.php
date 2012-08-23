<?php

App::uses('OfumAppController', 'Ofum.Controller');
class UsersController extends OfumAppController
{
    public function beforeFilter()
	{
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout', 'register', 'passwordReset', 'admin_impersonate');
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

				$data = array(
					'id'=>$this->User->id,
					'pass'=>$this->User->field('password')
				);
				$this->Cookie->write($this->cookieName, serialize($data), false, '1 year');


				$this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Invalid email address or password, try again'), 'notices/error');
			}
		}
	}

	public function logout()
	{
		if ($this->Cookie->read($this->cookieName))
			$this->Cookie->delete($this->cookieName);
		$this->redirect($this->Auth->logout());
	}

	public function index()
	{
		$this->set('users', $this->paginate());
	}

	public function register()
	{
		if ($this->request->isPost())
		{
			if (isset($this->request->data['User']['first_name']))
			{
				$this->fire('Plugin.Ofum.register_beforeValidate');
				if ($this->User->saveAll($this->request->data, array('validate'=>'only')))
				{
					$this->fire('Plugin.Ofum.register_afterValidate');

					if (!$this->request->data['Agency']['id'])
					{
						$this->User->Agency->save(array(
							'name'=>$this->request->data['Agency']['name']
						));
						$this->request->data['Agency']['id'] = $this->User->Agency->getLastInsertId();
					}

					$this->fire('Plugin.Ofum.register_beforeSaveAll');

					$result = $this->Usps->process($this->request->data['Location'][0]['addr1'], $this->request->data['Location'][0]['zip5'], $this->request->data['Location'][0]['addr2']);
					unset($this->request->data['Location']);

					$this->User->saveAll($this->request->data);
					$this->request->data['User']['id'] = $this->User->getLastInsertId();

					$this->User->UsersGroup->save(array(
						'user_id'=>$this->request->data['User']['id'],
						'group_id'=>1
					));

					$result['name'] = 'Home Address';
					$result['user_id'] = $this->request->data['User']['id'];
					$lid = $this->User->Location->process($result, $this);
					if ($lid)
						$this->User->save(array(
							'user_id'		=> $this->request->data['User']['id'],
							'home_address'	=> $lid
						));

					$this->fire('Plugin.Ofum.register_afterSaveAll');

					$this->Session->setFlash('You have successfully registered, please login below.', 'notices/success');
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

						if (empty($exist['User']['uuid']))
							$this->User->save(array(
								'id'=>$exist['User']['id'],
								'uuid'=>md5($exist['User']['first_name'].' '.$exist['User']['last_name'].' '.time())
							));

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

		switch($renderView)
		{
			case null:
			case 'Attended':
			case 'Conference':
				$this->User->contain(array(
					'Attending.Course.CourseType',
					'Attending.Course.Status',
					'Attending.Conference',
					'Attending.Payment',
					'Attending.Status',
					'Attending.User',
				));
			break;

			case 'Instructed':
				$this->User->contain(array(
					'Instructor.Instructing.Course.CourseType',
					'Instructor.Instructing.Course.Status',
					'Instructor.Instructing.Status',
				));

			break;

			case 'Account':
				$this->User->contain(array(
					'Agency',
					'HomeAddress',
				));
			break;

			case 'Invoices':
				$this->User->contain(array(
					'Payment.Status'
				));
			break;
		}

		$user = $this->User->read(null, $id);

		switch($renderView)
		{
			case 'Instructed':
				if (!empty($user['Instructor']['Instructing']))
					foreach($user['Instructor']['Instructing'] as $idx => $in)
						if ($in['status_id'] != 3)
							unset($user['Instructor']['Instructing'][$idx]);
			break;
		}

		$this->set('user', $user);

		if ($renderView)
		{
			$renderView=strtolower($renderView);
			$this->set('renderView', $renderView);
			$this->render('Users'.DS.'pages'.DS.$renderView);
		}
	}

	public function edit($id = null)
	{
		if ($id == null || $id != $this->Auth->user('id'))
			$id = $this->Auth->user('id');

		$user = $this->User->read(null, $id);
		$this->set('user', $user);

		if ($this->request->is('post') || $this->request->is('put'))
		{
			$this->User->create();
			if ($this->User->save($this->request->data))
			{
				$this->Session->setFlash('Successfully updated account details', 'notices/success');
				$this->redirect(array('action'=>'view', $id, 'account'));
			}
			else
				$this->Session->setFlash('Please correct the errors below to continue', 'notices/error');
		}
		else
		{
			$this->fire('Plugin.Ofum.view_beforeRead');
			$this->request->data = $user;
		}
	}

	//admin sections

	public function admin_index($show = 'admin_index', $id = null)
	{
		$this->set('show', $show);
		$this->set('id', $id);
	}

	public function admin_view($id = null, $renderView =null)
	{
		$this->User->id = $id;
		if (!$this->User->exists())
			throw new NotFoundException(__('Invalid user'));

		switch($renderView)
		{
			default:
				$this->User->contain(array(
					'Attending.Course.CourseType',
					'Attending.Course.Status',
					'Attending.Conference',
					'Attending.Payment',
					'Attending.Status',
					'Attending.User',
					'Payment.Status',
					'Instructor.Instructing.Course.CourseType',
					'Instructor.Instructing.Course.Status',
					'Instructor.Instructing.Status',
					'Agency',
					'HomeAddress.City',
					'HomeAddress.State',
					'UsersGroup.Group'
				));
				break;
		}

		$this->set('user', $this->User->read(null, $id));

		if ($renderView)
			$this->render('Users'.DS.'pages'.DS.$renderView);

	}

	public function admin_dataTable($type='admin_index', $id=null)
	{
		$this->datatable($type, $id);
	}

	public function dataTable($type='index', $id = null)
	{
		$conditions = array();
		$joins = array(
			array(
				'table'=>'agencies',
				'alias'=>'Agency',
				'type'=>'LEFT',
				'conditions'=>array(
					'Agency.id = User.agency_id'
			))
		);
		switch($type)
		{
			case 'instructor_search':
				$joins [] =array(
					'table'=>'instructors',
					'alias'=>'Instructor',
					'type'=>'LEFT',
					'conditions'=>array(
						'Instructor.user_id = User.id'
				));
				$joins [] =array(
					'table'=>'tiers',
					'alias'=>'Tier',
					'type'=>'LEFT',
					'conditions'=>array(
						'Tier.id = Instructor.tier_id'
				));
			break;

			case 'online':
				$conditions['User.last_action >'] = date('Y-m-d H:i:s', strtotime('-5 minutes'));
				$type= 'admin_index';
			break;

			case 'rtoday':
				$conditions['User.created >='] = date('Y-m-d H:i:s', strtotime('12:01 am'));
				$type= 'admin_index';
			break;

			case 'agency':
				$conditions['User.agency_id'] = $id;
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
				case 'instructor_search':
					switch($_GET['iSortCol_0'])
					{
						case 0: break;
						case 1: $order = array('User.last_name'=>$_GET['sSortDir_0']); break;
						case 2: $order = array('Agency.name'=>$_GET['sSortDir_0']); break;
						case 3: $order = array('Tier.id'=>$_GET['sSortDir_0']); break;
					}
				break;

				case 'agency':
					switch($_GET['iSortCol_0'])
					{
						case 0: $order = array('User.last_name'=>$_GET['sSortDir_0']); break;
						case 1: $order = array('User.title'=>$_GET['sSortDir_0']); break;
						case 2: $order = array('User.last_login'=>$_GET['sSortDir_0']); break;
						case 3: $order = array('User.created'=>$_GET['sSortDir_0']); break;
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
			$or[] = array('CONCAT(User.first_name, " ", User.last_name) LIKE'=>'%'.$_GET['sSearch'].'%');
			$or[] = array('User.email LIKE'=>$_GET['sSearch'].'%');
			$or[] = array('User.pid LIKE'=>$_GET['sSearch'].'%');
			$or[] = array('User.ssid LIKE'=>$_GET['sSearch'].'%');
			$or[] = array('Agency.name LIKE'=>$_GET['sSearch'].'%');

			if ($type == 'instructor_search')
			{
				$or[] = array('Tier.name LIKE'=>$_GET['sSearch'].'%');
				$or[] = array('Tier.short'=>$_GET['sSearch']);
			}
			$conditions[] = array('or'=>$or);
		}


		$found = $this->User->find('count', array(
			'conditions'=>$conditions,
			'joins'=>$joins
		));
		$courses = $this->User->find('all', array(
			'conditions'=>$conditions,
			'order'=>$order,
			'limit'=>$limit,
			'offset'=>$offset,
			'joins'=>$joins,
			'fields'=>'*'
		));

		//echo "/* ".print_r($order, true).' */';

		$this->set('found', $found);
		$this->set('users', $courses);
		$this->render('Users/tables'.DS.$type);
	}

	public function admin_edit($id)
	{
		if ($this->request->is('post') || $this->request->is('put'))
		{
			if ($this->User->save($this->request->data))
			{
				$this->Session->setFlash('Successfully edited user', 'notices/success');
				$this->redirect(array('action'=>'view', $id));
			}
		}
		else
		{
			$this->request->data = $this->User->read(null, $id);

		}
	}

	public function admin_setGroup($id)
	{
		if ($this->request->is('post') || $this->request->is('put'))
		{
			$exist = $this->User->UsersGroup->findByUserId($this->request->data['User']['id']);
			if ($exist)
			{
				$data = array(
					'id'=>$exist['UsersGroup']['id'],
					'group_id'=>$this->request->data['UsersGroup']['group_id']
				);
				$this->User->UsersGroup->save($data);
			}
			else
			{
				$data = array(
					'user_id'=>$this->request->data['User']['id'],
					'group_id'=>$this->request->data['UsersGroup']['group_id']
				);
				$this->User->UsersGroup->save($data);
			}

			$this->Session->setFlash('Updated Usergroup', 'notices/success');
			$this->redirect(array('action'=>'view', $id));
		}
		else
		{
			$this->User->contain(array('UsersGroup'));
			$this->request->data = $this->User->read(null, $id);
			$this->loadModel('Group');
			$this->set('groups', $this->Group->find('list'));
		}
	}

	public function admin_findDupes($id)
	{
		if ($this->request->is('post') || $this->request->is('put'))
		{
			foreach($this->request->data['User']['usercheck'] as $olduser => $merge)
			{
				if ($merge)
				{
					$this->User->contain(array('Instructor'));
					$newusr = $this->User->read(null, $id);

					//need to change all user_id matching $olduser to $id
					$this->loadModel('Ofcm.Attending');
					if (!$this->Attending->updateAll(
						array('Attending.user_id'=>$id),
						array('Attending.user_id'=>$olduser)
					))
						die('Attending Move Fail');

					$this->loadModel('Ofcm.Contact');
					if (!$this->Contact->updateAll(
						array('Contact.user_id'=>$id),
						array('Contact.user_id'=>$olduser)
					))
						die('Contact Move Fail');

					$this->loadModel('Ofcm.Instructing');
					if ($newusr['Instructor']['id'])
						if (!$this->Instructing->updateAll(
							array('Instructing.user_id'=>$id, 'Instructing.instructor_id'=>$newusr['Instructor']['id']),
							array('Instructing.user_id'=>$olduser)
						))
							die('Instructing Move Fail');

					$this->loadModel('Ofcm.Instructor');
					if (!$this->Instructor->deleteAll(
						array('Instructor.user_id'=>$olduser)
					))
						die('Instructors Delete Fail');

					$this->loadModel('LineItem');
					if (!$this->LineItem->updateAll(
						array('LineItem.user_id'=>$id),
						array('LineItem.user_id'=>$olduser)
					))
						die('LineItem Move Fail');

					$this->loadModel('Location');
					if (!$this->Location->updateAll(
						array('Location.user_id'=>$id),
						array('Location.user_id'=>$olduser)
					))
						die('Location Move Fail');

					$this->loadModel('Message');
					if (!$this->Message->updateAll(
						array('Message.to_user_id'=>$id),
						array('Message.to_user_id'=>$olduser)
					))
						die('Message to Move Fail');
					if (!$this->Message->updateAll(
						array('Message.from_user_id'=>$id),
						array('Message.from_user_id'=>$olduser)
					))
						die('Message from Move Fail');

					$this->loadModel('Note');
					if (!$this->Note->updateAll(
						array('Note.user_id'=>$id),
						array('Note.user_id'=>$olduser)
					))
						die('Note Move Fail');

					$this->loadModel('Payment');
					if (!$this->Payment->updateAll(
						array('Payment.user_id'=>$id),
						array('Payment.user_id'=>$olduser)
					))
						die('Payment Move Fail');

					$this->loadModel('Phone');
					if (!$this->Phone->updateAll(
						array('Phone.user_id'=>$id),
						array('Phone.user_id'=>$olduser)
					))
						die('Phone Move Fail');

					$this->loadModel('Post');
					if (!$this->Post->updateAll(
						array('Post.user_id'=>$id),
						array('Post.user_id'=>$olduser)
					))
						die('Post Move Fail');

					$this->loadModel('Post');
					if (!$this->Post->updateAll(
						array('Post.user_id'=>$id),
						array('Post.user_id'=>$olduser)
					))
						die('Post Move Fail');

					$this->loadModel('QuestionAnswer');
					if (!$this->QuestionAnswer->updateAll(
						array('QuestionAnswer.user_id'=>$id),
						array('QuestionAnswer.user_id'=>$olduser)
					))
						die('QuestionAnswer Move Fail');

					$this->loadModel('Studentlist');
					if (!$this->Studentlist->updateAll(
						array('Studentlist.user_id'=>$id),
						array('Studentlist.user_id'=>$olduser)
					))
						die('Studentlist Move Fail');

					$this->loadModel('TeleformData');
					if (!$this->TeleformData->updateAll(
						array('TeleformData.user_id'=>$id),
						array('TeleformData.user_id'=>$olduser)
					))
						die('TeleformData Move Fail');

					$this->loadModel('Ofum.UsersGroup');
					if (!$this->UsersGroup->deleteAll(
						array('UsersGroup.user_id'=>$olduser)
					))
						die('UsersGroup Delete Fail');

					//merge any better user details (if any)

					$oldusr = $this->User->read(null, $olduser);

					$this->User->create();
					$this->User->id = $id;
					if (!empty($oldusr['User']['pid']) && empty($newusr['User']['pid']))
						$this->User->saveField('pid', $oldusr['User']['pid']);

					if (!empty($oldusr['User']['ssid']) && empty($newusr['User']['ssid']))
						$this->User->saveField('ssid', $oldusr['User']['ssid']);

					if (!empty($oldusr['User']['dob']) && empty($newusr['User']['dob']))
						$this->User->saveField('dob', $oldusr['User']['dob']);

					if (!empty($oldusr['User']['title']) && empty($newusr['User']['title']))
						$this->User->saveField('title', $oldusr['User']['title']);

					if (!empty($oldusr['User']['main_phone']) && empty($newusr['User']['main_phone']))
						$this->User->saveField('main_phone', $oldusr['User']['main_phone']);
					if (!empty($oldusr['User']['other_phone']) && empty($newusr['User']['other_phone']))
						$this->User->saveField('other_phone', $oldusr['User']['other_phone']);

					if (!empty($oldusr['User']['home_address']) && empty($newusr['User']['home_address']))
						$this->User->saveField('home_address', $oldusr['User']['home_address']);

					if (!$this->User->delete($olduser))
						die('old user delete fail');
				}
			}

			$this->Session->setFlash('Successfully merged', 'notices/success');
			$this->redirect(array('action'=>'view', $id));
		}


		$this->User->contain();
		$user = $this->User->read(null, $id);


		$this->User->contain(array(
			'Attending',
			'Instructing',
			'Instructor',
			'LineItem',
			'Payment',
			'Location',
			'Contact',
			'UsersGroup',
			'Note',
			'Post',
			'TeleformData'
		));

		$conditions['conditions']= array('User.id not'=>$id);

		$ors = array();
		if ($user['User']['pid'])
			$ors['pid']=$user['User']['pid'];

		if ($user['User']['ssid'])
			$ors['ssid']=$user['User']['ssid'];

		if ($user['User']['dob'] != '1993-01-01' && $user['User']['dob'] != '0000-00-00')
			$ors['dob']=$user['User']['dob'];

		//$ors['email like']=substr($user['User']['email'],0,strpos($user['User']['email'], '@')).'%';

		if (!empty($ors))
			$conditions['conditions']['or']=$ors;


		$ands = array();
		if ($user['User']['first_name'] && $user['User']['last_name'])
		{
			$ands = array(
				'first_name'=>$user['User']['first_name'],
				'last_name'=>$user['User']['last_name']
			);
			$conditions['conditions']['and']=$ands;

			if (!$this->User->find('count', $conditions))
			{
				unset($conditions['conditions']['and']);
				$conditions['conditions']['first_name']=$user['User']['first_name'].' '.$user['User']['last_name'];

				if (!$this->User->find('count', $conditions))
				{
					unset($conditions['conditions']['first_name']);
				}
			}
		}
		else
		{
			if(strpos($user['User']['first_name'], ' '))
			{
				list($fname, $lname) = split(' ', $user['User']['first_name']);
				$ands = array(
					'first_name like'=>$fname,
					'last_name like'=>$lname
				);
				$conditions['conditions']['and']=$ands;

				if (!$this->User->find('count', $conditions))
				{
					unset($conditions['conditions']['and']);
					$conditions['conditions']['first_name']=$user['User']['first_name'];

					if (!$this->User->find('count', $conditions))
					{
						unset($conditions['conditions']['first_name']);
					}
				}
			}
			else
			{
				$conditions['conditions']['first_name']=$user['User']['first_name'];
				if (!$this->User->find('count', $conditions))
				{
					unset($conditions['conditions']['first_name']);
				}
			}
		}

		//die(pr($conditions));

		if (!empty($conditions['conditions']))
		{
			if (count($conditions['conditions'])==1)
			{
				$this->Session->setFlash('No duplicates found', 'notices/success');
				$this->redirect(array('action'=>'view', $id));
			}
			$users = $this->User->find('all', $conditions);
			if (empty($users))
			{
				$this->Session->setFlash('No duplicates found', 'notices/success');
				$this->redirect(array('action'=>'view', $id));
			}
			$this->set('users', $users);
		}
		else
			$this->set('users', array());


		$this->User->contain(array(
			'Attending.Course.CourseType',
			'Attending.Course.Status',
			'Attending.Conference',
			'Attending.Payment',
			'Attending.Status',
			'Attending.User',
			'Payment.Status',
			'Instructor.Instructing.Course.CourseType',
			'Instructor.Instructing.Course.Status',
			'Instructor.Instructing.Status'
		));
		$this->set('user', $this->User->read(null, $id));
		//}

	}

	public function admin_impersonate($id)
	{
		$user = $this->User->read(null, $id);
		$this->Auth->login($user['User']);
		$this->redirect('/');
	}

	//instructor sections
	public function instructor_view($id = null)
	{
		if ($id == null)
			$id = $this->Auth->user('id');

		$this->User->contain(array(
			'Agency',
			'Instructor.Tier'
		));
		$currentUser = $this->User->read(null, $id);


		$this->loadModel('Ofcm.Instructing');
		$this->Instructing->contain(array('Course.CourseType', 'Status'));
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
		));

		$this->Instructing->contain(array('Course.CourseType', 'Status'));
		$currentUser['Instructing']['approved'] = $this->Instructing->find('all', array(
			'conditions'=>array(
				'user_id'=>$id,
				'Instructing.status_id'=>3,
				'Course.enddate > now()',
			),
			'order'=>array(
				'Course.startdate'
			)
		));


		$this->set('currentUser', $currentUser);

		$this->loadModel('Instructor');
		$this->Instructor->contain(array(
			'InstructorHistory'
		));
		$data = $this->Instructor->findByUserId($id);
		$this->set('instructor', $data);

		$results = $this->Instructor->Tier->TierRequirement->test($data);
		$this->Instructor->Tier->contain(array(
			'TierRequirement'
		));
		$tiers =$this->Instructor->Tier->find('all');
		$this->set(compact('tiers', 'results'));

		$onlything = true;
		$reviewid = 0;
		foreach($tiers as $tier)
			if ($tier['Tier']['id'] == $data['Instructor']['tier_id'])
			{
				foreach($tier['TierRequirement'] as $tr)
				{
					if ($tr['review'] == true && $results[$tier['Tier']['id']][$tr['id']]['test'] == 0)
					{
						$reviewid = $tr['id'];
					}
					elseif (!$results[$tier['Tier']['id']][$tr['id']]['test'])
					{
						$onlything = false;
					}
				}
			}

		if ($onlything && $reviewid)
			$this->set('needreview', $reviewid);

	}
	public function instructor_profile($id = null)
	{
		$this->User->contain(array(
			'Agency',
			'Instructor.Tier'
		));
		$this->set('user', $this->User->read(null, $id));
	}

	public function admin_findByEmail($term)
	{
		if (!empty($term))
		{
			$this->User->contain(array('Agency'));
			$this->set('user', $this->User->find('first', array(
				'conditions'=>array(
					'User.email'=>$term
				)
			)));
			$this->response->type('ajax');
		}
		else
			$this->set('user', null);
	}
}