<?php

App::uses('AppController', 'Controller');
class OFUMAppController extends AppController
{
    public function beforeFilter()
	{
        //Configure::load('OFUM.usermin');

		parent::beforeFilter();
    }

}

