<?php

App::uses('Component', 'Controller/Component');
class ControllerListComponent extends Component
{
	function getPluginList()
	{
		return App::objects('Plugins');
	}

	function getControllerList($plugin = null)
	{
		if ($plugin)
			return App::objects($plugin.'.controllers');
		else
			return App::objects('controllers');
	}

	function getControllerActions($plugin = null, $controller = null)
	{
		if ($controller != null)
		{
			if ($plugin == null)
				App::import('Controller', substr($controller, 0, -10));
			else
				App::import('Controller', $plugin.'.'.substr($controller, 0, -10));
			$actions = get_class_methods($controller) or array();
			foreach($actions as $k => $v)
				if ($v[0] == '_')
					unset($actions[$k]);
			$parentActions = get_class_methods('AppController');
			return array_diff($actions, $parentActions);
		}
		return false;
	}

}