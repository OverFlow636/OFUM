<?php

$uactive = $gactive = $pactive = false;
switch (strtolower($this->request->params['controller']))
{
	case 'users':			$uactive = true; break;
	case 'groups':			$gactive = true; break;
	case 'ofumpermissions':	$pactive = true; break;
}

echo $this->Html->nestedList(array(
	$this->Html->link('Users', array('admin'=>true, 'controller'=>'Users', 'action'=>'index'), array('class'=>$uactive?'active':'')),
	$this->Html->link('Groups', array('admin'=>true, 'controller'=>'Groups', 'action'=>'index'), array('class'=>$gactive?'active':'')),
	$this->Html->link('Permissions', array('admin'=>true, 'controller'=>'OfumPermissions', 'action'=>'index'), array('class'=>$pactive?'active':'')),
	$this->Html->link('Logout', array('admin'=>true, 'controller'=>'Users', 'action'=>'logout'), array('class'=>'logout')),
),array(
	'id'=>'mainNav'
));
