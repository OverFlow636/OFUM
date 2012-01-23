<?php

App::uses('CakeEventListener', 'Event');

class UserRegisterListener implements CakeEventListener
{
	public function implementedEvents()
	{
		return array(
			'Plugin.Ofum.register_beforeValidate'	=> 'registerBeforeValidate',
			'Plugin.Ofum.register_afterSaveAll'		=> 'registerAfterSaveAll'
		);
	}

	public function registerBeforeValidate($event)
	{
		if (!empty($event->subject->request->data['Agency']['name']) && empty($event->subject->request->data['Agency']['id']))
		{
			//try to find a matching agency, or just let the saveAll make a new one
			$this->Agency = ClassRegistry::init('Agency');

			die('fix agency!');
		}

		if (!empty($event->subject->request->data['Agency']['name']) && !empty($event->subject->request->data['Agency']['id']))
			unset($event->subject->request->data['Agency']['name']);

	}

	public function registerAfterSaveAll($event)
	{
		$this->Reaction = ClassRegistry::init('Reaction');
		$emails = $this->Reaction->findAllByEventAndReactionTypeId('Plugin.Ofum.register_afterSaveAll', 1);

		if ($emails)
			foreach($emails as $email)
				$event->subject->_sendTemplateEmail(unserialize($email['Reaction']['args']), $event->subject->request->data);
	}
}