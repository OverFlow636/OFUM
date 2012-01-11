<?php

// Routes for standard actions

Router::connect('/login', array('plugin' => 'ofum', 'controller' => 'Users', 'action' => 'login'));
Router::connect('/logout', array('plugin' => 'ofum', 'controller' => 'Users', 'action' => 'logout'));
Router::connect('/register', array('plugin' => 'ofum', 'controller' => 'Users', 'action' => 'register'));




//Router::connect('/dashboard', array('plugin' => 'usermin', 'controller' => 'umdashboard', 'action' => 'index'));
