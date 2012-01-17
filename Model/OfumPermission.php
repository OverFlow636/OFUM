<?php
App::uses('OfumAppModel', 'Ofum.Model');
/**
 * OfumPermission Model
 *
 * @property Group $Group
 */
class OfumPermission extends OfumAppModel
{


	public function test()
	{
		die('works');
	}

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Ofum.Group'
	);
}
