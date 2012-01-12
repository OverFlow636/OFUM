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
		$bts = Configure::read('Ofum.UserModel.belongsTo');
		if (!empty($bts))
			foreach($bts as $bt)
				$this->belongsTo[] = $bt;

		$hms = Configure::read('Ofum.UserModel.hasMany');
		if (!empty($hms))
			foreach($hms as $hm)
				$this->hasMany[] = $hm;

		$v = Configure::read('Ofum.UserModel.validate');
		if (!empty($v))
			$this->validate = $v;

		$vf = Configure::read('Ofum.UserModel.virtualFields');
		if (!empty($vf))
			$this->virtualFields = $vf;

		parent::__construct();
	}

	public $virtualFields = array(
		'name' => 'CONCAT(User.first_name," ",User.last_name)'
	);


	public $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
