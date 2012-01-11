<?php
App::uses('OfumAppController', 'Ofum.Controller');
/**
 * Groups Controller
 *
 */
class GroupsController extends OfumAppController
{

	/**
	* index method
	*
	* @return void
	*/
	public function index()
	{
		$this->Group->recursive = 0;
		$this->set('groups', $this->paginate());
	}

	/**
	* view method
	*
	* @param string $id
	* @return void
	*/
	public function view($id = null) {
	$this->Group->id = $id;
	if (!$this->Group->exists()) {
	throw new NotFoundException(__('Invalid group'));
	}
	$this->set('group', $this->Group->read(null, $id));
	}

	/**
	* add method
	*
	* @return void
	*/
	public function add() {
	if ($this->request->is('post')) {
	$this->Group->create();
	if ($this->Group->save($this->request->data)) {
	$this->flash(__('Group saved.'), array('action' => 'index'));
	} else {
	}
	}
	}

	/**
	* edit method
	*
	* @param string $id
	* @return void
	*/
	public function edit($id = null) {
	$this->Group->id = $id;
	if (!$this->Group->exists()) {
	throw new NotFoundException(__('Invalid group'));
	}
	if ($this->request->is('post') || $this->request->is('put')) {
	if ($this->Group->save($this->request->data)) {
	$this->flash(__('The group has been saved.'), array('action' => 'index'));
	} else {
	}
	} else {
	$this->request->data = $this->Group->read(null, $id);
	}
	}

	/**
	* delete method
	*
	* @param string $id
	* @return void
	*/
	public function delete($id = null) {
	if (!$this->request->is('post')) {
	throw new MethodNotAllowedException();
	}
	$this->Group->id = $id;
	if (!$this->Group->exists()) {
	throw new NotFoundException(__('Invalid group'));
	}
	if ($this->Group->delete()) {
	$this->flash(__('Group deleted'), array('action' => 'index'));
	}
	$this->flash(__('Group was not deleted'), array('action' => 'index'));
	$this->redirect(array('action' => 'index'));
	}
	/**
	* staff_index method
	*
	* @return void
	*/
	public function staff_index() {
	$this->Group->recursive = 0;
	$this->set('groups', $this->paginate());
	}

	/**
	* staff_view method
	*
	* @param string $id
	* @return void
	*/
	public function staff_view($id = null) {
	$this->Group->id = $id;
	if (!$this->Group->exists()) {
	throw new NotFoundException(__('Invalid group'));
	}
	$this->set('group', $this->Group->read(null, $id));
	}

	/**
	* staff_add method
	*
	* @return void
	*/
	public function staff_add() {
	if ($this->request->is('post')) {
	$this->Group->create();
	if ($this->Group->save($this->request->data)) {
	$this->flash(__('Group saved.'), array('action' => 'index'));
	} else {
	}
	}
	}

	/**
	* staff_edit method
	*
	* @param string $id
	* @return void
	*/
	public function staff_edit($id = null) {
	$this->Group->id = $id;
	if (!$this->Group->exists()) {
	throw new NotFoundException(__('Invalid group'));
	}
	if ($this->request->is('post') || $this->request->is('put')) {
	if ($this->Group->save($this->request->data)) {
	$this->flash(__('The group has been saved.'), array('action' => 'index'));
	} else {
	}
	} else {
	$this->request->data = $this->Group->read(null, $id);
	}
	}

	/**
	* staff_delete method
	*
	* @param string $id
	* @return void
	*/
	public function staff_delete($id = null) {
	if (!$this->request->is('post')) {
	throw new MethodNotAllowedException();
	}
	$this->Group->id = $id;
	if (!$this->Group->exists()) {
	throw new NotFoundException(__('Invalid group'));
	}
	if ($this->Group->delete()) {
	$this->flash(__('Group deleted'), array('action' => 'index'));
	}
	$this->flash(__('Group was not deleted'), array('action' => 'index'));
	$this->redirect(array('action' => 'index'));
	}
}
