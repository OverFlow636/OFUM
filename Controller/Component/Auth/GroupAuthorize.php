<?php

App::uses('BaseAuthorize', 'Controller/Component/Auth');
App::uses('OFUMPermission', 'OFUM.Model');

class GroupAuthorize extends BaseAuthorize {

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
        if (isset($this->settings['authorizeAll']) && $this->settings['authorizeAll']) {
            return true;
        }

        if ($user['group_id'] == 5)
		{
            // superadmin user is cool
            return true;
        }

        $actionRequested = Router::parse($request->here(false));

        $this->_log("user: ${user['email']} is trying to access: p(${actionRequested['plugin']}) c(${actionRequested['controller']}) a(${actionRequested['action']}) ");

        // get permissions for the role
        $permClass = new OFUMPermission();
        $conditions = array('conditions' => array('group_id' => $user['group_id']));
        //TODO: Use cache here
        $permissionsForUserRole = $permClass->find('all', $conditions);

        //this should be optimized (tree or cache)
        foreach ($permissionsForUserRole as $perm)
		{
            $this->_log("checking permission " . $perm['OFUMPermission']['id'] . ' = p(' . $perm['OFUMPermission']['plugin'] . ') c(' . $perm['OFUMPermission']['controller'] . ') a(' . $perm['OFUMPermission']['action'] . ')');
            // strict validation, not using * yet
            if ($perm['OFUMPermission']['plugin']		== '*' || (strtoupper($actionRequested['plugin'])		== strtoupper($perm['OFUMPermission']['plugin'])) &&
                $perm['OFUMPermission']['controller']	== '*' || (strtoupper($actionRequested['controller'])	== strtoupper($perm['OFUMPermission']['controller'])) &&
                $perm['OFUMPermission']['action']		== '*' || (strtoupper($actionRequested['action'])		== strtoupper($perm['OFUMPermission']['action'])))
			{
                $this->_log("permission matches, returning true if allowed");
                return ($perm['OFUMPermission']['allowed'] == 1);
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