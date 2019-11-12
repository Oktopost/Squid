======
Basics
======

.. contents:: On this Page


Installing Squid
================


To install as a composer requirement

.. code-block:: shell-session
	
	composer require oktopost/squid


To clone repository

.. code-block:: shell-session
	
	git clone git@github.com:Oktopost/Squid.git
	
With optional composer install and running tests

.. code-block:: shell-session
	
	cd Squid
	composer install
	composer test


Basic Use Case
==============

First we will need to create an instance of :code:`Squid/MySql` and configure a new connection. Note that the connection
will only be opened when the first command is executed and not during config.

.. code-block:: php
	:linenos:

	$mysql = new MySql();
	$mysql->addConnector(
		[
			'host'	=> 'localhost',
			'user'	=> 'admin',
			'pass'	=> '1234',
			'db'	=> 'application'
		]);
	
	$connector = $mysql->getConnector();

The :code:`$connector` object is an instance with a reference to a connection and does not represent the connection itself.
Any new object obtained from :code:`$mysql->getConnector();` will reuse the same connection unless multiple connections are configured.

Now we can use this connector to create a new command.

**Selecting Data**

.. code-block:: php
	:linenos:
	
	$select = $connector
		->select()
		->from('Users')
		->byField('Email', $email)
		->byField('IsValid', true);

	$users = $select->query();

	if (!$users)
	{
		// ...
	}
	else
	{
		$ids = array_column($users, 'ID);
		// ...
	}

**Inserting Data**

.. code-block:: php
	:linenos:
	
	$mysql->getConnector()
		->insert()
		->into('Users')
		->values([
			'ID'	=> NULL,
			'Name'	=> 'Bob'
		])
		->executeDml();


Decorating Connection
=====================

A connection can be decorated by passing an instance of :code:`Squid\MySql\Connection\IMySqlExecuteDecorator`.
Any MySQL query that is executed via this connection, will be passed through the decorator first. This way a
full control is given over the execution flow. 

For example, the decorator :code:`TimeoutDecorator` below, will print out any command running for more then 0.2 seconds into the output buffer.


.. code-block:: php
	:linenos:
	
	<?php
	use Squid\MySql;
	use Squid\MySql\Connection\IMySqlExecutor;
	
	
	require_once 'vendor/autoload.php';
	
	
	class TimeoutDecorator implements MySql\Connection\IMySqlExecuteDecorator
	{
        /** @var float */
		private $timeout;
		
		/** @var IMySqlExecutor */
		private $child;
		
		
		private function handleTimedOut(string $cmd, array $bind, float $runtime): void
		{
			$bind = json_encode($bind);
			$runtime = round($runtime, 3);
			
			echo "Command `$cmd` with $bind took {$runtime} seconds\n";
		}
		
		
		public function __construct(float $timeoutInSeconds = 0.25)
		{
			$this->timeout = $timeoutInSeconds;
		}
		
		/**
		 * Always called at least once before execute
		 * @param IMySqlExecutor|null $child
		 */
		public function init(IMySqlExecutor $child = null)
		{
			$this->child = $child;
		}
		
		/**
		 * Called on execution, child must be invoked, otherwise the command will not be executed.
		 * @param string $cmd
		 * @param array $bind
		 * @return mixed
		 */
		public function execute($cmd, array $bind = [])
		{
			$startAt = microtime(true);
			
			$result = $this->child->execute($cmd, $bind);
			
			$runtime = microtime(true) - $startAt;
			
			if ($runtime > $this->timeout)
			{
				$this->handleTimedOut($cmd, $bind, $runtime);
			}
			
			return $result;
		}
	}
	
	$mysql = new MySql();
	$mysql->addConnector(
		[
			'host'	=> 'localhost',
			'user'	=> 'admin',
			'pass'	=> '1234',
			'db'	=> 'application'
		])
		->addDecorator(
			new TimeoutDecorator(0.2)
			// Passing class name TimeoutDecorator::class will also work
		);
	
	$mysql->getConnector()
		->select()
		->columnsExp('SLEEP(?)', 0.3)
		->queryInt(); 

The output will be

.. code-block:: shell-session
	
	Command `SELECT SLEEP(?) ` with [0.3] took 0.301 seconds

