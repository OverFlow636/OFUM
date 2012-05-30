<?php
App::uses('CakeEventListener', 'Event');

class UserViewListener implements CakeEventListener
{
	public function implementedEvents()
	{
		return array(
			'Plugin.Ofum.view_beforeRead'	=> 'viewBeforeRead',
			'Plugin.Ofum.admin_view_beforeRead'	=> 'adminViewBeforeRead'
		);
	}

	public function viewBeforeRead($event)
	{
		$event->subject->User->contain(array(
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
			'HomeAddress',
		));
	}

	public function adminViewBeforeRead($event)
	{
		$event->subject->User->contain(array(
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
	}
}
