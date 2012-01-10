<?php
App::uses('OFUMAppModel', 'OFUM.Model');
/**
 * Group Model
 *
 * @property Group $ParentGroup
 * @property Group $ChildGroup
 * @property User $User
 */
class Group extends OFUMAppModel
{

	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'lft' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'rght' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public $belongsTo = array(
		'ParentGroup' => array(
			'className' => 'Group',
			'foreignKey' => 'parent_id'
		)
	);


	public $hasMany = array(
		'ChildGroup' => array(
			'className' => 'Group',
			'foreignKey' => 'parent_id'
		),
		'User',
		'OFUMPermission'
	);

}
