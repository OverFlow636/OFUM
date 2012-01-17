<?php
App::uses('OfumAppController', 'Ofum.Controller');
/**
 * Groups Controller
 *
 */
class GroupsController extends OfumAppController {

/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold;


	public function admin_index()
	{
		$this->Group->recursive = 0;
		$this->set('groups', $this->paginate());
	}

}
