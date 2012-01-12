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
		if (Configure::read('Ofum.trackLastAction'))
		{
			$this->User = ClassRegistry::init('Ofum.User', 'Model');
			$this->User->id = $user['id'];
			$this->User->saveField('last_action', date('Y-m-d H:i:s'));
		}

        if (isset($this->settings['authorizeAll']) && $this->settings['authorizeAll'])
            return true;

        $permClass = new OfumPermission();
		//TODO: pull permissions of all parents up the tree
        $perms = $permClass->find('all', array(
			'conditions' => array(
				'group_id' => $user['group_id']
			)
		));
		$action = Router::parse($request->here(false));
        foreach ($perms as $perm)
		{
            if ($perm['OfumPermission']['plugin']		== '*' || (strtoupper($action['plugin'])		== strtoupper($perm['OfumPermission']['plugin'])) &&
                $perm['OfumPermission']['controller']	== '*' || (strtoupper($action['controller'])	== strtoupper($perm['OfumPermission']['controller'])) &&
                $perm['OfumPermission']['action']		== '*' || (strtoupper($action['action'])		== strtoupper($perm['OfumPermission']['action'])))
			{
                return ($perm['OfumPermission']['allowed'] == 1);
            }
        }

        return false;
    }

}