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
		$this->User = ClassRegistry::init('Ofum.User', 'Model');
		$this->UsersGroup = ClassRegistry::init('Ofum.UsersGroup', 'Model');

		$this->UserGroup = ClassRegistry::init('Ofum.User', 'Model');

		if (Configure::read('Ofum.trackLastAction'))
		{
			$this->User->id = $user['id'];
			$this->User->saveField('last_action', date('Y-m-d H:i:s'));
		}

        if (isset($this->settings['authorizeAll']) && $this->settings['authorizeAll'])
            return true;

        $permClass = new OfumPermission();
		$grp = $this->UsersGroup->findByUserId($user['id']);
		if ($grp)
			$grp = $grp['UsersGroup']['group_id'];
		else
		{
			$this->UsersGroup->save(array(
				'user_id'=>$user['id'],
				'group_id'=>1
			));
			$grp = 1;
		}
		$userGroupTree = $permClass->Group->getPath($grp, array('id'));

		foreach($userGroupTree as $group)
		{
			$perms = $permClass->find('all', array(
				'conditions' => array(
					'group_id' => $group['Group']['id']
				)
			));

			$action = Router::parse($request->here(false));
			foreach ($perms as $perm)
			{
				if ($perm['OfumPermission']['plugin']		== '*' || (strtoupper($action['plugin'])		== strtoupper($perm['OfumPermission']['plugin'])) &&
					$perm['OfumPermission']['controller']	== '*' || (strtoupper($action['controller'].'Controller')	== strtoupper($perm['OfumPermission']['controller'])) &&
					$perm['OfumPermission']['action']		== '*' || (strtoupper($action['action'])		== strtoupper($perm['OfumPermission']['action'])))
				{
					return ($perm['OfumPermission']['allowed'] == 1);
				}
			}
		}

        return false;
    }

}