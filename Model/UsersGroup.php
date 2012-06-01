<?php

App::uses('OfumAppModel', 'Ofum.Model');
class UsersGroup extends OfumAppModel
{
	public $useStateSitePrefix = true;

	public $belongsTo = array(
		'Ofum.Group',
		'Ofum.User'
	);

}
