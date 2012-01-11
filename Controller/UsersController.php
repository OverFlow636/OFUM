<?php

App::uses('OfumAppController', 'Ofum.Controller');
class UsersController extends OfumAppController
{
    public function beforeFilter()
	{
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout', 'register');
    }

	public function login()
	{
		if ($this->request->isPost())
		{
			//die(pr($this->request->data));
			if ($this->Auth->login())
			{
				$this->User->id = $this->Auth->user('id');
				$this->User->saveField('last_login', date('Y-m-d H:i:s'));
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
			
			//fire before validate event
			if ($this->User->saveAll($this->request->data, array('validate'=>'only')))
			{
				//fire before save event
				$this->User->saveAll($this->request->data);
				//fire after save event
			}
		}



	}


}