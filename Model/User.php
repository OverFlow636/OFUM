<?php

App::uses('OfumAppModel', 'Ofum.Model');
class User extends OfumAppModel
{

	public $belongsTo = array(
		'Agency',
		'HomeAddress'=>array(
			'className'=>'Location',
			'foreignKey'=>'home_address'
		)
	);

	public $hasOne = array(
		'Phone',
		'Ofcm.Instructor',
		'Ofum.UsersGroup',
	);

	public $hasMany = array(
		'Ofcm.Attending',
		'Ofcm.Contact',
		'CourseRequest',
		'GenericTrack',
		'LineItem',
		'Location',
		'Note',
		'Payment',
		'TeleformData',
		'Ofcm.Instructing',
		'Message'=>array('foreignKey'=>'to_user_id'),
		'Post'
	);

	public function beforeSave()
	{
		if (isset($this->data[$this->alias]['password']))
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        return true;
	}

	public function __construct()
	{
		$modelVars = Configure::read('Ofum.UserModel');
		if (!empty($modelVars))
			foreach($modelVars as $var => $value)
				$this->$var = $value;

		if (Configure::read('Ofum.useGroups'))
		{
			die('using grps');
			$this->belongsTo[] = 'Ofum.Group';
		}

		$this->actsAs[] = 'Containable';
		parent::__construct();
		$this->virtualFields['name'] = 'CONCAT('.$this->alias.'.first_name," ",'.$this->alias.'.last_name)';
	}

	function identical($field = array(), $compare_field=null )
	{
		foreach($field as $idx => $value)
		{
			if ($value !== $this->data[$this->alias][$compare_field])
				return false;
			else
				continue;
		}
		return true;
	}


}
