<?php
$this->layout = 'admin';

$this->start('sidebar');
echo '
	<ul class="sideNav">
		<li>'.$this->Html->link(__('Edit Permission'), array('action' => 'edit', $perm['OfumPermission']['id'])).'</li>
		<li>'.$this->Html->link(__('Delete Permission'), array('action' => 'delete', $perm['OfumPermission']['id']),null,'are you sure?').'</li>
	</ul>';
$this->end();


$this->start('breadcrumbs');
echo '<a href="/ofum/OfumPermissions/">Permissions</a>';
$this->end();


?>
<h3>Permission</h3>

<table>
	<tr>
		<th>Property</th>
		<th>Value</th>
	</tr>

	<tr>
		<td>Plugin:</td>
		<td><?php echo $perm['OfumPermission']['plugin']?></td>
	</tr>

	<tr>
		<td>Controller:</td>
		<td><?php echo $perm['OfumPermission']['controller']?></td>
	</tr>

	<tr>
		<td>Action:</td>
		<td><?php echo $perm['OfumPermission']['action']?></td>
	</tr>

	<tr>
		<td>Allowed:</td>
		<td><?php echo $perm['OfumPermission']['allowed']?'<font color=green>Allowed</font>':'<font color=red>Denied</font>' ?></td>
	</tr>
</table>