==========
queryCount
==========

.. code-block:: php

	public function queryCount(): int|null

Execute the given query as ``SELECT COUNT(*)`` and return the result.
	
----------

.. rubric:: Return
	
| Number of rows in the result set. 

Depending on the type of the query, the count method may be slightly different. ``LIMIT``, ``GROUP BY``, ``HAVING`` and ``UNION`` 
will affect how the query is generated. See examples for more info. 

If the result set was empty, null will be returned. They may happen only if ``LIMIT`` or ``HAVING`` clauses are present. 
	
----------

.. rubric:: Examples

Execute a simple count command.

.. code-block:: php
	:linenos:
	
	$totalLoggedInUsers = $select
		->from('User')
		->byFields([
			'IsLoggedIn'	=> 1
		])
		->queryCount();
	
	// SELECT COUNT(*) FROM User WHERE IsLoggedIn = ?, [1]
	// $totalLoggedInUsers = 738

| If ``GROUP BY`` is present, a ``COUNT(DISTINCT ...)`` on the expressions from ``GROUP BY`` will be executed instead.
| The returned value should much the number of rows the query with ``GROUP BY`` would produce, if executed.  

.. code-block:: php
	:linenos:
	
	$count = $select
		->from('User')
		->groupBy('IsLoggedIn')
		->queryCount();
	
	// SELECT COUNT(DISTINCT IsLoggedIn) FROM User
	// $count = 2

The ``LIMIT`` clause is not ignored when executing ``queryCount``, however, because ``SELECT COUNT(*)`` will always 
return a single row, only if ``LIMIT offset, count`` have an offset greater then 0 or count equal to 0, the result set will be empty, and **null** will be returned.

.. code-block:: php
	:linenos:
	
	$count = $select
		->from('User')
		->limitBy(0)
		// or ->limit(1, ...)
		->queryCount();
	
	// SELECT COUNT(*) FROM User LIMIT 0, 0
	// $count = null

``HAVING`` clause is not ignored and will be applied on the query. For example, with ``HAVING COUNT(*) > 2``, 
if ``COUNT(*)`` is not greater then 2, an empty result set will be selected and ``null`` will be returned

.. code-block:: php
	:linenos:
	
	$count = $select
		->from('User')
		->having('COUNT(*) > 2')
		->queryCount();
	
	// SELECT COUNT(*) FROM User HAVING COUNT(*) > 2
	// $count = 3,4,5... or null

| If a ``UNION`` or ``DISTINCT`` are present, a subquery is generated instead.
| Note that for a ``UNION`` command, this may result in bad preference and, in general, should be avoided. 

.. code-block:: php
	:linenos:

	$selectAccount->from('Account');
	
	$count = $select
		->from('User')
		->union($selectAccount)
		->queryCount();
	
	// SELECT COUNT(*) FROM (SELECT * FROM User UNION ALL SELECT * FROM Account) as a
	// $count = 873 (users count + accounts count)
		
.. code-block:: php
	:linenos:
	
	$count = $select
		->distinct()
		->column('Name')
		->from('User')
		->queryCount();
	
	// SELECT COUNT(*) FROM (SELECT DISTINCT Name FROM User) as a
	// $count = 678

.. note::
	
	``ORDER BY``, ``LOCK IN SHARE MODE`` and ``FOR UPDATE``  will not be included in the count query. 