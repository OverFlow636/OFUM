<?php

App::uses('OfumAppModel', 'Ofum.Model');
class User extends OfumAppModel
{

	public function beforeSave()
	{
		if (isset($this->data[$this->alias]['password']))
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        return true;
	}

	public function __construct()
	{
		$modelVars = Configure::read('Ofum.UserModel');
		foreach($modelVars as $var => $value)
			$this->$var = $value;

		$this->belongsTo[] = 'Ofum.Group';
		$this->actsAs[] = 'Containable';
		parent::__construct();
	}

	public $virtualFields = array(
		'name' => 'CONCAT(User.first_name," ",User.last_name)'
	);

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
