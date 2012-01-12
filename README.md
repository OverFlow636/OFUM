# OverFlow User Management

  There are tons of user management plugins for cake, but now that 2.1 is on its way i've decided to start another.
  The plugin is in its early states, but is also already in use for one the sites I run.

  The main reasons I wanted to start a new plugin versus use one of the existing ones are as follows.

  * I wanted a completly generic plugin, no application specific code should be in the plugin. Therefore the plugin can be dropped into *any* application and just work.
  * I wanted to be able to configure basic options like use username or email for auth.
  * I wanted permissions bundled with the plugin with inheritence.

  The plugin is written to make replacing your existing user management system as easily as possible.

## Features

  * User table completely configurable, no default fields other than id, (username or email), password, and group_id
  * Ability to configure the relationiships for the included user model, without changing the plugins code!
  * Basic and admin views included, but overwriting at least the basic views is recommended
  * Many many events called in the user model and controller so that the main application can acccess any required data

## To Install

  * Clone the project into your `app/plugins/Ofum` folder
  * run the sql in the `config/schema` dir
  * load the plugin in your bootstrap
  * In your AppController's $components variable add
	`'Auth'=>array(
		'loginAction' => array(
			'plugin' => 'ofum',
			'controller' => 'Users',
			'action' => 'login'
		),
		'authorize' => array(
			'Ofum.Group'
		),
		'authenticate'=>array(
			'Form'=>array(
				'userModel' => 'Ofum.User'
			)
		)
	)`

## To Contribute

  Please fork away and send pulls, I need all the help I can get.

