========
queryAll
========

.. code-block:: php

	public function queryAll($isAssoc = false): array

Execute the query and return the entire result set in a single array.

----------

.. rubric:: Parameters

* **$isAssoc**: *int* | *bool*  = false

	| Set to ``true`` to return an array of associative rows, and ``false`` to return each row as a numeric array.
	| By default ``$isAssoc = false`` and an array of numeric arrays is returned.
	| Alternatively, some of the ``PDO::FETCH_*`` consts can be passed as well.

----------

.. rubric:: Return
	
| Array of the entire result set.
| Each row is represented as an associative or numeric array based on the ``$isAssoc`` parameter.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select
		->column('ID', 'Name')
		->from('User')
		->byField('IsLoggedIn', true);


	$users = $select->queryAll(false);
	// $users = [ [1, 'Bob'], [2, 'Jen'] ]

	
	$users = $select->queryAll(true);
	// $users = [ ['ID' => 1, 'Name' => 'Bob'], ['ID' => 2, 'Name' => 'Jen'] ]