<?php

App::uses('OfumAppController', 'Ofum.Controller');
class OfumPermissionsController extends OfumAppController
{

	function index()
	{
		$this->redirect(array('admin'=>true,'action'=>'index'));
	}

	function admin_index()
	{
		$this->OfumPermission->recursive = 1;
		$this->set('perms', $this->paginate());
	}

	function admin_add()
	{
		if ($this->request->is('post'))
		{
			if ($this->OfumPermission->save($this->request->data))
			{
				$this->Session->setFlash('Permission added', 'success');
				$this->redirect(array('admin'=>true, 'action'=>'index'));
			}
			else
			{
				$this->Session->setFlash('Error adding permission', 'error');
				$this->redirect(array('admin'=>true, 'action'=>'index'));
			}
		}
		else
		{
			$this->_setRequiredData();
		}
	}

	function admin_view($id = null)
	{
		$this->set('perm', $this->OfumPermission->read(null, $id));
	}

	function admin_edit($id = null)
	{
		if ($this->request->is('post'))
		{
			if ($this->OfumPermission->save($this->request->data))
			{
				$this->Session->setFlash('Permission added', 'success');
				$this->redirect(array('admin'=>true, 'action'=>'index'));
			}
			else
			{
				$this->Session->setFlash('Error adding permission', 'error');
				$this->redirect(array('admin'=>true, 'action'=>'index'));
			}
		}
		else
		{
			$this->request->data = $this->OfumPermission->read(null, $id);
			$this->_setRequiredData();
		}
	}

	function admin_delete($id = null)
	{
		if ($this->OfumPermission->delete($id))
		{
			$this->Session->setFlash('Permission deleted', 'success');
			$this->redirect(array('admin'=>true, 'action'=>'index'));
		}
		else
		{
			$this->Session->setFlash('Error deleting permission', 'error');
			$this->redirect(array('admin'=>true, 'action'=>'index'));
		}
	}

	function _setRequiredData()
	{
		$this->ControllerList = $this->Components->load('Ofum.ControllerList');
		$pluginList = array_merge(array(null), $this->ControllerList->getPluginList());
		foreach($pluginList as $plugin)
		{
			if ($plugin == null)
				$plugins[$plugin] = 'None';
			else
				$plugins[$plugin] = $plugin;

			$pcl = $this->ControllerList->getControllerList($plugin);
			foreach($pcl as $controller)
			{
				$acts = $this->ControllerList->getControllerActions($plugin, $controller);
				foreach($acts as $act)
					$actionList[$act] = $act;
				$actions[$plugin.'.'.$controller] = $actionList;


				if ($plugin == null)
					$controllers['Application'][$controller] = $controller;
				else
					$controllers[$plugin][$controller] = $controller;
			}
		}

		$controllers = array_merge(array('*'=>'*'), $controllers);
		$actions = array_merge(array('*'=>'*'), $actions);

		$this->set('plugins', $plugins);
		$this->set('controllers', $controllers);
		$this->set('actions', $actions);

		//die(pr($this));
		$this->set('groups', $this->OfumPermission->Group->generateTreeList());
	}
}