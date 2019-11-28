.. _insert:

======
Insert
======

| An `INSERT` command is generated using an instance of a class that implements ``Squid\MySql\Command\ICmdInsert``.
| Below is a standard example of how to generate a simple `INSERT` command with Squid.

.. code-block:: php

	$mysql = new MySql();
	$mysql->addConnector(
		'main', 
		[
			'host'	=> 'localhost',
			'user'	=> 'admin',
			'pass'	=> 'pass',
			'db'	=> 'application'
		]);
	
	
	$connector = $mysql->getConnector('main');
	
	$connector->insert()
		->into('Users')
		->values([
			'Name'	=> 'John',
			'Age'	=> 23,
			'Email'	=> 'john@example.com'
		])
		->executeDml();

----------

.. rubric:: Methods

.. toctree::
	:maxdepth: 1
	
	Insert/ignore
	Insert/defaultValues
	Insert/into
	Insert/values
	Insert/valuesBulk
	Insert/valuesExp
	Insert/asSelect
	Insert/exec
