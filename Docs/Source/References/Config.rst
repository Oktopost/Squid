=============
Configuration
=============

.. toctree::
	:maxdepth: 2
	
	Config/values
	Config/properties

---------

When creating a new connector with ``\Squid\MySql::addConnector``, a set of configuration values can be passed for this 
connector. 

.. code-block:: php
	:linenos:

	$mysql = new MySql();
	$mysql->addConnector(
		[
			'host'	=> 'localhost',
			'user'	=> 'admin',
			'pass'	=> 'pass',
			'db'	=> 'application'
		]);

| Some of the config keys have aliases (like `user` and `username`), and all of them are case insensitive.
| For example, the code below and above are equivalent.

.. code-block:: php
	:linenos:

	$mysql = new MySql();
	$mysql->addConnector(
		[
			'HOST'     => 'localhost',
			'username' => 'admin',
			'password' => 'pass',
			'dbname'   => 'application'
		]);

| Each set of configuration values is attached to it's connector, and can not be altered later on.
| Here we will create 2 different connectors, each with its own config.

.. code-block:: php
	:linenos:

	$mysql = new MySql();
	$mysql->addConnector(
		[
			'HOST'		=> 'localhost',
			'username'	=> 'admin',
			'password'	=> 'pass',
			'version'	=> '5.6',
			'dbname'	=> 'users'
		]);

	$mysql = new MySql();
	$mysql->addConnector(
		'cache',
		[
			'HOST'		=> '10.0.0.23',
			'username'	=> 'admin',
			'password'	=> '1234',
			'version'	=> '5.6'
		]);

	$usersConnector = $mysql->getConnector('main');
	$cacheConnector = $mysql->getConnector('cache');

| Any command generated from the ``$usersConnector`` object, will be sent to **localhost**, and any command from ``$cacheConnector`` will be sent to the server **10.0.0.23**.
| You can also see that the MySQL version and password are different, and no default DB specified for ``cache`` connector. 

.. note::

	The expression ``$mysql->addConnector([/* ... */])`` is equivalent to ``$mysql->addConnector('main', [/* ... */])``