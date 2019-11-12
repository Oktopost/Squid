===========
queryColumn
===========

.. code-block:: php

	public function queryColumn(bool $failOnMultipleResults = true): array

Return the value of the first column in a numeric array.

----------

.. rubric:: Parameters

* **$failOnMultipleResults**: *bool* = true

	If set to **true** and more than one column is selected, an exception will be thrown.

----------

.. rubric:: Return

| Array containing all the values from the first column in the result set. If the result was empty, an empty array is returned.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$ids = $select
		->column('ID')
		->from('User')
		->byField('IsLoggedIn', true)
		->limitBy(5)
		->queryColumn();
	
	// $ids = [1, 2, 4, 29, 34]