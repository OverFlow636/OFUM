<?php

// Routes for standard actions

Router::connect('/Login', array('plugin' => 'ofum', 'controller' => 'Users', 'action' => 'login'));
Router::connect('/Logout', array('plugin' => 'ofum', 'controller' => 'Users', 'action' => 'logout'));
Router::connect('/Register', array('plugin' => 'ofum', 'controller' => 'Users', 'action' => 'register'));

Router::connect('/Profile/*', array('plugin' => 'ofum', 'controller' => 'Users', 'action' => 'view'));



//Router::connect('/dashboard', array('plugin' => 'usermin', 'controller' => 'umdashboard', 'action' => 'index'));
