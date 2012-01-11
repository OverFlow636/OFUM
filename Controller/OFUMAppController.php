<?php

App::uses('AppController', 'Controller');
class OfumAppController extends AppController
{
	public $components = array(
		'Session',
		'Auth'
	);
	
    public function beforeFilter()
	{
        //Configure::load('OFUM.usermin');

		parent::beforeFilter();
    }

}

