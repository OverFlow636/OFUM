<?php
$this->layout = 'cakeDefault';
?>
<div class="groups view">
<h2><?php  echo __('Group');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($group['Group']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($group['Group']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Parent Group'); ?></dt>
		<dd>
			<?php echo $this->Html->link($group['ParentGroup']['name'], array('controller' => 'groups', 'action' => 'view', $group['ParentGroup']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lft'); ?></dt>
		<dd>
			<?php echo h($group['Group']['lft']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rght'); ?></dt>
		<dd>
			<?php echo h($group['Group']['rght']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($group['Group']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($group['Group']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Group'), array('action' => 'edit', $group['Group']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Group'), array('action' => 'delete', $group['Group']['id']), null, __('Are you sure you want to delete # %s?', $group['Group']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parent Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Ofum Permissions'), array('controller' => 'ofum_permissions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ofum Permission'), array('controller' => 'ofum_permissions', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Groups');?></h3>
	<?php if (!empty($group['ChildGroup'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Parent Id'); ?></th>
		<th><?php echo __('Lft'); ?></th>
		<th><?php echo __('Rght'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($group['ChildGroup'] as $childGroup): ?>
		<tr>
			<td><?php echo $childGroup['id'];?></td>
			<td><?php echo $childGroup['name'];?></td>
			<td><?php echo $childGroup['parent_id'];?></td>
			<td><?php echo $childGroup['lft'];?></td>
			<td><?php echo $childGroup['rght'];?></td>
			<td><?php echo $childGroup['created'];?></td>
			<td><?php echo $childGroup['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'groups', 'action' => 'view', $childGroup['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'groups', 'action' => 'edit', $childGroup['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'groups', 'action' => 'delete', $childGroup['id']), null, __('Are you sure you want to delete # %s?', $childGroup['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Child Group'), array('controller' => 'groups', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Users');?></h3>
	<?php if (!empty($group['User'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Uuid'); ?></th>
		<th><?php echo __('Ssid'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('First Name'); ?></th>
		<th><?php echo __('Last Name'); ?></th>
		<th><?php echo __('Password'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('Teex'); ?></th>
		<th><?php echo __('Pid'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Dob'); ?></th>
		<th><?php echo __('CellPhone'); ?></th>
		<th><?php echo __('HomePhone'); ?></th>
		<th><?php echo __('Agency Id'); ?></th>
		<th><?php echo __('Last Login'); ?></th>
		<th><?php echo __('Verified'); ?></th>
		<th><?php echo __('Bio'); ?></th>
		<th><?php echo __('Txst Vendor Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Home Address'); ?></th>
		<th><?php echo __('Attending Count'); ?></th>
		<th><?php echo __('Payment Count'); ?></th>
		<th><?php echo __('Last Action'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($group['User'] as $user): ?>
		<tr>
			<td><?php echo $user['id'];?></td>
			<td><?php echo $user['uuid'];?></td>
			<td><?php echo $user['ssid'];?></td>
			<td><?php echo $user['title'];?></td>
			<td><?php echo $user['first_name'];?></td>
			<td><?php echo $user['last_name'];?></td>
			<td><?php echo $user['password'];?></td>
			<td><?php echo $user['email'];?></td>
			<td><?php echo $user['teex'];?></td>
			<td><?php echo $user['pid'];?></td>
			<td><?php echo $user['created'];?></td>
			<td><?php echo $user['dob'];?></td>
			<td><?php echo $user['cellPhone'];?></td>
			<td><?php echo $user['homePhone'];?></td>
			<td><?php echo $user['agency_id'];?></td>
			<td><?php echo $user['last_login'];?></td>
			<td><?php echo $user['verified'];?></td>
			<td><?php echo $user['bio'];?></td>
			<td><?php echo $user['txst_vendor_id'];?></td>
			<td><?php echo $user['group_id'];?></td>
			<td><?php echo $user['modified'];?></td>
			<td><?php echo $user['home_address'];?></td>
			<td><?php echo $user['attending_count'];?></td>
			<td><?php echo $user['payment_count'];?></td>
			<td><?php echo $user['last_action'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'users', 'action' => 'view', $user['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'users', 'action' => 'edit', $user['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'users', 'action' => 'delete', $user['id']), null, __('Are you sure you want to delete # %s?', $user['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Ofum Permissions');?></h3>
	<?php if (!empty($group['OfumPermission'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Plugin'); ?></th>
		<th><?php echo __('Controller'); ?></th>
		<th><?php echo __('Action'); ?></th>
		<th><?php echo __('Params'); ?></th>
		<th><?php echo __('Allowed'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($group['OfumPermission'] as $ofumPermission): ?>
		<tr>
			<td><?php echo $ofumPermission['id'];?></td>
			<td><?php echo $ofumPermission['group_id'];?></td>
			<td><?php echo $ofumPermission['user_id'];?></td>
			<td><?php echo $ofumPermission['plugin'];?></td>
			<td><?php echo $ofumPermission['controller'];?></td>
			<td><?php echo $ofumPermission['action'];?></td>
			<td><?php echo $ofumPermission['params'];?></td>
			<td><?php echo $ofumPermission['allowed'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'ofum_permissions', 'action' => 'view', $ofumPermission['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'ofum_permissions', 'action' => 'edit', $ofumPermission['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'ofum_permissions', 'action' => 'delete', $ofumPermission['id']), null, __('Are you sure you want to delete # %s?', $ofumPermission['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Ofum Permission'), array('controller' => 'ofum_permissions', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
