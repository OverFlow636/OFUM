<?php
$this->layout = 'admin';


$this->start('sidebar');
echo '
	<ul class="sideNav">
		<li>'.$this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])).'</li>
		<li>'.$this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])).'</li>
		<li>'.$this->Html->link(__('List Users'), array('action' => 'index')).'</li>
		<li>'.$this->Html->link(__('New User'), array('action' => 'add')).'</li>
	</ul>';
$this->end();


$this->start('breadcrumbs');
echo '<a href="/ofum/Users/">Users</a> &raquo; <a href="#" class="active">View</a>';
$this->end();

pr($user);