<?php

// Routes for standard actions

Router::connect('/login', array('plugin' => 'OFUM', 'controller' => 'Users', 'action' => 'login'));
Router::connect('/logout', array('plugin' => 'OFUM', 'controller' => 'Users', 'action' => 'logout'));
Router::connect('/register', array('plugin' => 'OFUM', 'controller' => 'Users', 'action' => 'register'));


Router::connect('/users', array('plugin'=>'OFUM', 'controller'=>'Users'));
Router::connect('/users/:action/*', array('plugin'=>'OFUM', 'controller'=>'Users'));

//Router::connect('/dashboard', array('plugin' => 'usermin', 'controller' => 'umdashboard', 'action' => 'index'));
