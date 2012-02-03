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

}