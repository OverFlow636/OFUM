<?php
$this->layout = 'cakeDefault';

echo $this->Form->create('User', array(
	'action'=>'login'
));

echo $this->Form->input(Configure::read('Ofum.usernameField'));
echo $this->Form->input('password');

if (Configure::read('Ofum.rememberEnabled'))
	echo $this->Form->input('remember', array(
		'type'=>'checkbox',
		'label'=>'Remember Me',
		'checked'=>Configure::read('Ofum.rememberDefault')
	));

echo $this->Form->end('Login');
