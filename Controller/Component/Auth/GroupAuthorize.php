<?php

App::uses('BaseAuthorize', 'Controller/Component/Auth');
App::uses('OfumPermission', 'Ofum.Model');

class GroupAuthorize extends BaseAuthorize
{
    /**
     * Checks if a Permission matching plugin, controller and
     * action exists and is allowed to access for the user's
     * role.
     * 'superadmin' user is always authorized
     *
     * @param type $user
     * @param CakeRequest $request
     * @return type
     */
    public function authorize($user, CakeRequest $request)
	{
		$actionRequested = Router::parse($request->here(false));

        $this->_log("user: ${user['email']} is trying to access: p(${actionRequested['plugin']}) c(${actionRequested['controller']}) a(${actionRequested['action']}) ");


		$this->User = ClassRegistry::init('Ofum.User', 'Model');
		$this->User->id = $user['id'];
		$this->User->saveField('last_action', date('Y-m-d H:i:s'));

        if (isset($this->settings['authorizeAll']) && $this->settings['authorizeAll'])
            return true;

        if ($user['group_id'] == 5)
		{
            return true;
        }

        // get permissions for the role
        $permClass = new OfumPermission();
        $conditions = array('conditions' => array('group_id' => $user['group_id']));
        //TODO: Use cache here
        $permissionsForUserRole = $permClass->find('all', $conditions);

        //this should be optimized (tree or cache)
        foreach ($permissionsForUserRole as $perm)
		{
            $this->_log("checking permission " . $perm['OfumPermission']['id'] . ' = p(' . $perm['OfumPermission']['plugin'] . ') c(' . $perm['OfumPermission']['controller'] . ') a(' . $perm['OfumPermission']['action'] . ')');
            // strict validation, not using * yet
            if ($perm['OfumPermission']['plugin']		== '*' || (strtoupper($actionRequested['plugin'])		== strtoupper($perm['OfumPermission']['plugin'])) &&
                $perm['OfumPermission']['controller']	== '*' || (strtoupper($actionRequested['controller'])	== strtoupper($perm['OfumPermission']['controller'])) &&
                $perm['OfumPermission']['action']		== '*' || (strtoupper($actionRequested['action'])		== strtoupper($perm['OfumPermission']['action'])))
			{
                $this->_log("permission matches, returning true if allowed");
                return ($perm['OfumPermission']['allowed'] == 1);
            }
        }
        $this->_log("no rules matched. user is not allowed ");

        return false;
    }

    private function _log($var)
	{
        if (isset($this->settings['debug']) && $this->settings['debug'])
            $this->controller()->log($var, LOG_DEBUG);
    }

}