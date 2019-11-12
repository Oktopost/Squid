.. _select_queryIterator:

=============
queryIterator
=============

.. code-block:: php

	public function queryIterator($isAssoc = true): iterable

Returns an iterator that can be used to go over the entire result set.

Similar to :ref:`select_queryWithCallback`, ``queryIterator`` will use less memory than selecting the entire data set at once.
Only enough memory for a single element is used per iteration.

----------	

.. rubric:: Parameters

* **$isAssoc**: *bool* = true

	If true, iterator will return associative array of values. Otherwise, a numeric array.

----------	

.. rubric:: Return

``iterable`` object that can be used in a loop.

----------	

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select
		->column('Id', 'Name')
		->from('User');
	
	foreach ($select->queryIterator() as $row)
	{
		var_dump($row);
	}

	// ['ID' => 1, 'Name' => 'John']
	// ['ID' => 2, 'Name' => 'Garry']
	// ['ID' => 3, 'Name' => 'Bob']
	// ....
