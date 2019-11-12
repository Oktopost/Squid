---------
Connector
---------


Connector references to an instance implementing the interface :code:`Squid\MySql\IMySqlConnector`
and obtained usually in the following way:

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

	$connector = $mysql->getConnector();


A connector is not a wrapper for MySQL connection. Rather it holds a reference to a connection and used 
as a *Simple Factory* object to create and initialize different MySQL command objects.

For each of the common types of MySQL queries there is a different Squid class. For example, for the **SELECT** command we
would use an instance implementing :code:`Squid\MySql\Command\ICmdSelect` and for **DELETE** :code:`Squid\MySql\Command\ICmdDelete`.

Example of obtaining such objects from the connector:

.. code-block:: php
	:linenos:

	$connector = $mysql->getConnector();

	$select = $connector->select();
	$delete = $connector->delete();


.. rubric:: Methods

.. contents:: 
	:local:
	
select
======

	Create a :ref:`select` object to generate **SELECT** queries.

	.. code-block:: php

		public function select(): Squid\MySql\Command\ICmdSelect
	
insert
======

	Create an object for an **INSERT** query.

	.. code-block:: php

		public function insert(): Squid\MySql\Command\ICmdInsert
	
update
======

	Create an object for an **UPDATE** query.

	.. code-block:: php

		public function update(): Squid\MySql\Command\ICmdUpdate
	
upsert
======

	Create an object for an **INSERT ... ON DUPLICATE KEY UPDATE ...** query. This object can be used when updating bulk data sets, or
	inserting an object that may already exist in the DB.

	.. code-block:: php

		public function upsert(): Squid\MySql\Command\ICmdInsert
	
delete
======

	Create an object for a **DELETE** query.

	.. code-block:: php
		
		public function delete(): Squid\MySql\Command\ICmdDelete
	
direct
======

	Generate any SQL query.

	.. code-block:: php

		public function direct(?string $command = null, array $bind = []): Squid\MySql\Command\ICmdDirect
	
	* **$columns**: *string* | *null*  
	
		Optional MySQL command.  
	
	* **$bind**:  *mixed* | *array* | *false* 
		
		Optional bind values for the command.

	Passing any parameters to this method is equivalent to 

	.. code-block:: php
	
		$connector->direct()
			->command($command, $bind);
	
create
======

	Create an object for the **CREATE TABLE** query.
		
	.. code-block:: php

		public function create(): Squid\MySql\Command\ICmdCreate
	
lock
====

	Get a command object used to work with the `GET_LOCK()` and `RELEASE_LOCK()` methods.

	.. code-block:: php

		public function lock(): Squid\MySql\Command\ICmdLock
	
transaction
===========

	Get a command object to manage the current transaction.
	As the transaction objects do not share a state, it's best to use only one transaction object per connection at any given time.

	.. code-block:: php

		public function transaction(): Squid\MySql\Command\ICmdTransaction
	
db
====

	A set of commands used to manipulate the current database.

	.. code-block:: php

		public function db(): Squid\MySql\Command\ICmdDB
	
bulk
====

	An object that can be used to execute multiple commands in one go. 

	.. code-block:: php

		public function bulk(): Squid\MySql\Command\ICmdMultiQuery
	
close
=====

	If the current connection is open, close it.

	.. code-block:: php

		public function close(): void
	
name
====

	Get the name of the config used to initialize this connector.

	.. code-block:: php

		public function name(): string
