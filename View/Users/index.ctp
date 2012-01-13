<div class="users index">
	<h2><?php echo __('Users');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('uuid');?></th>
			<th><?php echo $this->Paginator->sort('ssid');?></th>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th><?php echo $this->Paginator->sort('first_name');?></th>
			<th><?php echo $this->Paginator->sort('last_name');?></th>
			<th><?php echo $this->Paginator->sort('password');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
			<th><?php echo $this->Paginator->sort('teex');?></th>
			<th><?php echo $this->Paginator->sort('pid');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('dob');?></th>
			<th><?php echo $this->Paginator->sort('cellPhone');?></th>
			<th><?php echo $this->Paginator->sort('homePhone');?></th>
			<th><?php echo $this->Paginator->sort('agency_id');?></th>
			<th><?php echo $this->Paginator->sort('last_login');?></th>
			<th><?php echo $this->Paginator->sort('verified');?></th>
			<th><?php echo $this->Paginator->sort('bio');?></th>
			<th><?php echo $this->Paginator->sort('txst_vendor_id');?></th>
			<th><?php echo $this->Paginator->sort('group_id');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th><?php echo $this->Paginator->sort('home_address');?></th>
			<th><?php echo $this->Paginator->sort('attending_count');?></th>
			<th><?php echo $this->Paginator->sort('payment_count');?></th>
			<th><?php echo $this->Paginator->sort('last_action');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['uuid']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['ssid']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['title']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['first_name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['last_name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['password']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['teex']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['pid']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['created']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['dob']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['cellPhone']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['homePhone']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($user['Agency']['name'], array('controller' => 'agencies', 'action' => 'view', $user['Agency']['id'])); ?>
		</td>
		<td><?php echo h($user['User']['last_login']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['verified']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['bio']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['txst_vendor_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($user['Group']['name'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id'])); ?>
		</td>
		<td><?php echo h($user['User']['modified']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['home_address']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['attending_count']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['payment_count']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['last_action']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $user['User']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Agencies'), array('controller' => 'agencies', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Agency'), array('controller' => 'agencies', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Attendings'), array('controller' => 'attendings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attending'), array('controller' => 'attendings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Contacts'), array('controller' => 'contacts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contact'), array('controller' => 'contacts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Requests'), array('controller' => 'course_requests', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Request'), array('controller' => 'course_requests', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Generic Tracks'), array('controller' => 'generic_tracks', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Generic Track'), array('controller' => 'generic_tracks', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Instructings'), array('controller' => 'instructings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Instructing'), array('controller' => 'instructings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Instructors'), array('controller' => 'instructors', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Instructor'), array('controller' => 'instructors', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Line Items'), array('controller' => 'line_items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Line Item'), array('controller' => 'line_items', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Locations'), array('controller' => 'locations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Location'), array('controller' => 'locations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Notes'), array('controller' => 'notes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Note'), array('controller' => 'notes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Payments'), array('controller' => 'payments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Payment'), array('controller' => 'payments', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Phones'), array('controller' => 'phones', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Phone'), array('controller' => 'phones', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Teleform Datas'), array('controller' => 'teleform_datas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Teleform Data'), array('controller' => 'teleform_datas', 'action' => 'add')); ?> </li>
	</ul>
</div>
