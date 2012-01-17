<?php
$this->layout = 'admin';

$this->start('sidebar');
echo '
	<ul class="sideNav">
		<li>'.$this->Html->link(__('New Permission'), array('action' => 'add')).'</li>
	</ul>';
$this->end();


$this->start('breadcrumbs');
echo '<a href="/ofum/OfumPermissions/">Permissions</a> &raquo; <a href="/ofum/OfumPermissions/view/'.$this->request->data['OfumPermission']['id'].'" >View</a> &raquo; <a href="#" class="active">Edit</a>';
$this->end();


echo $this->Html->tag('h3', "Editing A Permission");

echo "<table>";
echo $this->Form->create('OfumPermission', array(
	'action'=>'edit',
	'inputDefaults'=>array(
		'before'=>'<tr><td>',
		'between'=>'</td><td>',
		'after'=>'</td></tr>'
	)
));
echo $this->Form->input('id');

echo $this->Form->input('group_id');
echo $this->Form->input('plugin');
echo $this->Form->input('controller');
echo $this->Form->input('action');

echo $this->Form->input('allowed');

echo '<tr><td colspan=2>'.$this->Form->end('Add').'</td></tr></table>';
