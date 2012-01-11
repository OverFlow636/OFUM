<?php

App::uses('AppController', 'Controller');
class OfumAppController extends AppController
{

    public function beforeFilter()
	{
        Configure::load('Ofum.ofum');

		parent::beforeFilter();
    }

}

