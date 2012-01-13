<?php
$this->layout = 'admin';

$this->start('sidebar');
echo '
	<ul class="sideNav">
		<li>'.$this->Html->link(__('New Group'), array('action' => 'add')).'</li>
	</ul>';
$this->end();


$this->start('breadcrumbs');
echo '<a href="#">Dashboard</a> &raquo; <a href="#" class="active">Print resources</a>';
$this->end();







?>
<h3>User List</h3>
<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
			<th><?php echo $this->Paginator->sort('group');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($users as $group): ?>
	<tr>
		<td><?php echo h($group['User']['id']); ?>&nbsp;</td>
		<td><?php echo h($group['User']['email']); ?>&nbsp;</td>
		<td><?php echo h($group['Group']['name']); ?>&nbsp;</td>
		<td><?php echo h($group['User']['created']); ?>&nbsp;</td>
		<td class="action">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $group['User']['id']), array('class'=>'view')); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $group['User']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $group['User']['id']), array('class'=>'delete'), __('Are you sure you want to delete # %s?', $group['Group']['id'])); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	<tr>
		<td><?php echo $this->Paginator->prev('<<', array(), null, array('class' => 'prev disabled'));?></td>
		<td colspan="3"><center>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</center></td>
	
	<td><?php echo $this->Paginator->next('>>', array(), null, array('class' => 'next disabled')); ?></td>
	</tr>
</table>
