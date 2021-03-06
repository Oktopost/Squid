.. _select:

======
Select
======

| A `SELECT` command is generated using an instance of a class that implements ``Squid\MySql\Command\ICmdSelect``.
| Below is a standard example of how to generate a simple `SELECT` command with Squid.

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
	
	$users = $connector->select()
		->from('Users')
		->byField('Status', 'active')
		->orderBy('Name')
		->limitBy(10)
		->query();

----------

List of entry points grouped by scope

.. rubric:: `SELECT ...`

.. toctree::
	:maxdepth: 1
	
	Select/distinct
	Select/column
	Select/columns
	Select/columnsExp
	Select/columnAs
	Select/columnAsExp


.. rubric:: `FROM ...`

.. toctree::
	:maxdepth: 1
	
	Select/from
	Select/join
	Select/leftJoin
	Select/rightJoin


.. rubric:: `WHERE ...`

.. toctree::
	:maxdepth: 1

	Select/byId
	Select/byField
	Select/byFields
	Select/where
	Select/whereIn
	Select/whereNotIn
	Select/whereExists
	Select/whereNotExists
	Select/whereBetween
	Select/whereNotEqual
	Select/whereLess
	Select/whereLessOrEqual
	Select/whereGreater
	Select/whereGreaterOrEqual


.. rubric:: `GROUP BY ...`

.. toctree::
	:maxdepth: 1

	Select/groupBy
	Select/withRollup


.. rubric:: `HAVING ...`

.. toctree::
	:maxdepth: 1

	Select/having


.. rubric:: `ORDER BY ...`

.. toctree::
	:maxdepth: 1

	Select/orderBy
	Select/orderByAsc
	Select/orderByDesc


.. rubric:: `LIMIT ...`

.. toctree::
	:maxdepth: 1

	Select/limit
	Select/limitBy
	Select/page


.. rubric:: `UNION ...`

.. toctree::
	:maxdepth: 1

	Select/union
	Select/unionAll


.. rubric:: Execution

.. toctree::
	:maxdepth: 1

	Select/query
	Select/queryNumeric
	Select/queryAll
	Select/queryRow
	Select/queryColumn
	Select/queryScalar
	Select/queryInt
	Select/queryFloat
	Select/queryBool
	Select/queryExists
	Select/queryCount
	Select/queryWithCallback
	Select/queryIterator
	Select/queryIteratorBulk
	Select/queryValuesMap
	Select/queryValuesGroup
	Select/queryRecordsMap
	Select/queryRecordsGroup


.. rubric:: Additional Query Flags

.. toctree::
	:maxdepth: 1

	Select/forUpdate
	Select/lockInShareMode


.. rubric:: Util methods

.. toctree::
	:maxdepth: 1

	Select/assemble
	Select/bind
	Select/debug