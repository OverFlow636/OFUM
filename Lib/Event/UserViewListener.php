<?php
App::uses('CakeEventListener', 'Event');

class UserViewListener implements CakeEventListener
{
	public function implementedEvents()
	{
		return array(
			'Plugin.Ofum.view_beforeRead'	=> 'viewBeforeRead'
		);
	}

	public function viewBeforeRead($event)
	{
		/*$event->subject->User->contain(array(
			'Attending.Course.CourseType',
			'Attending.Course.Status',
			'Attending.Conference',
			'Attending.Payment',
			'Attending.Status',
			'Attending.User',
			'Payment.Status',
			'Instructing.Course.CourseType',
			'Instructing.Course.Status',
			'Instructing.Status',
		));*/
	}
}
