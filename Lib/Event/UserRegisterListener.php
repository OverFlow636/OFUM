<?php

App::uses('CakeEventListener', 'Event');

class UserRegisterListener implements CakeEventListener
{
	public function implementedEvents()
	{
		return array(
			'Plugin.Ofum.register_beforeValidate'	=> 'registerBeforeValidate',
			'Plugin.Ofum.register_beforeSaveAll'	=> 'registerBeforeSaveAll',
			'Plugin.Ofum.register_afterSaveAll'		=> 'registerAfterSaveAll'
		);
	}

	public function registerBeforeValidate($event)
	{

	}

	public function registerBeforeSaveAll($event)
	{


	}

	public function registerAfterSaveAll($event)
	{

	}
}