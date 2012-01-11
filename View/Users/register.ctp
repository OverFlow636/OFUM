<?php
$this->layout = 'cakeDefault';

echo $this->Form->create('User', array(
	'action'=>'register'
));

echo $this->Form->input(Configure::read('Ofum.usernameField'));
echo $this->Form->input('password');
echo $this->Form->input('password_confirm', array(
	'type'=>'password'
));

echo $this->Form->input('first_name');
echo $this->Form->input('last_name');

echo $this->Form->end('Login');
