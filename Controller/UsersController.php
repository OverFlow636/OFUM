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

	public function login()
	{
		if ($this->request->isPost())
		{
			//die(pr($this->request->data));
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

	public function indexx()
	{
		$this->set('users', $this->paginate());
	}

	public function register()
	{
		if ($this->request->isPost())
		{
			$this->request->data['User']['group_id'] = 1;

			$this->getEventManager()->dispatch(new CakeEvent('Plugin.Ofum.register_beforeValidate', $this));
			if ($this->User->saveAll($this->request->data, array('validate'=>'only')))
			{
				$this->getEventManager()->dispatch(new CakeEvent('Plugin.Ofum.register_afterValidate', $this));

				$this->getEventManager()->dispatch(new CakeEvent('Plugin.Ofum.register_beforeSaveAll', $this));
				$this->User->saveAll($this->request->data);
				$this->request->data['User']['id'] = $this->User->getLastInsertId();
				$this->getEventManager()->dispatch(new CakeEvent('Plugin.Ofum.register_afterSaveAll', $this));
			}
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
}