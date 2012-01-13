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


?>
<div class="groups view">
<h2><?php  echo __('User');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($user['Group']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($user['Group']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group'); ?></dt>
		<dd>
			<?php echo $this->Html->link($user['Group']['name'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($user['Group']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($user['Group']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
