<?php
$this->layout = 'admin';

$this->start('sidebar');
echo '
	<ul class="sideNav">
		<li>'.$this->Html->link(__('New Permission'), array('action' => 'add')).'</li>
	</ul>';
$this->end();


$this->start('breadcrumbs');
echo '<a href="/ofum/OfumPermissions/">Permissions</a>';
$this->end();







?>
<h3>Permission List</h3>
<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('group_id');?></th>
			<th><?php echo $this->Paginator->sort('plugin');?></th>
			<th><?php echo $this->Paginator->sort('controller');?></th>
			<th><?php echo $this->Paginator->sort('action');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($perms as $group): ?>
	<tr>
		<td><?php echo $this->Html->link(h($group['Group']['name']), array('controller'=>'Groups','action' => 'view', $group['Group']['id']), array('class'=>'view')); ?>&nbsp;</td>
		<td><?php echo h($group['OfumPermission']['plugin']); ?>&nbsp;</td>
		<td><?php echo h($group['OfumPermission']['controller']); ?>&nbsp;</td>
		<td><?php echo h($group['OfumPermission']['action']); ?>&nbsp;</td>
		<td class="action">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $group['OfumPermission']['id']), array('class'=>'view')); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $group['OfumPermission']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $group['OfumPermission']['id']), array('class'=>'delete'), __('Are you sure you want to delete # %s?', $group['OfumPermission']['id'])); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	<tr>
		<td colspan="999">
			<table>
				<tr>
					<td><?php echo $this->Paginator->prev('<<', array(), null, array('class' => 'prev disabled'));?></td>
					<td><center>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</center></td>

	<td><?php echo $this->Paginator->next('>>', array(), null, array('class' => 'next disabled')); ?></td>
	</tr></table></td></tr>
</table>
