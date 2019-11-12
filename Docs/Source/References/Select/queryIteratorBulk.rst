=================
queryIteratorBulk
=================

.. code-block:: php

	public function queryIteratorBulk(int $size = 100, $isAssoc = true): \Iterator

This function is similar to :ref:`select_queryIterator`, except that the iterator will return an array of records
instead of a single record. Each iteration (excluding the last one) will have ``$size`` elements. 

----------	

.. rubric:: Parameters

* **$size**: *int* = 100

	Size of the bulk array to return on each iteration.

* **$isAssoc**: *bool* = true

	If true, iterator will return associative array of values. Otherwise, a numeric array.
	
----------	

.. rubric:: Return

Iterator that can be used in a loop.
	
----------	

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select
		->column('Id', 'Name')
		->from('User');
	
	foreach ($select->queryIteratorBulk(2) as $row)
	{
		var_dump($row);
	}

	// [ 
	//		['ID' => 1, 'Name' => 'John'],
	//		['ID' => 2, 'Name' => 'Garry']
	// ]
	// [
	//		['ID' => 3, 'Name' => 'Bob'],
	// 		['ID' => 4, 'Name' => 'David'],
	// ]
	// ...
