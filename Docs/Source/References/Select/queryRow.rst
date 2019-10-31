--------
queryRow
--------

.. code-block:: php

	public function queryRow($isAssoc = false, bool $failOnMultipleResults = true): array

Execute the query and return the first row from the result set.


.. rubric:: Parameters

* **$isAssoc**: *int* | *bool*  = false

	Set to ``true`` to return an associative array, and ``false`` to return numeric.

* **$failOnMultipleResults**: *bool*  = true

	Expect exactly one or zero rows. If more then one row is selected, throw an exception. 
	Note that if 0 rows selected, an exception will **not** be thrown.   
	

.. rubric:: Return
	
Single row as a numeric or associative array based on the ``$isAssoc`` parameter, or false if no rows selected at all.

If the generated query is not unique, it's advised to append the ``LIMIT`` clause. 
Even so only the first row will be returned, the entire data is still queried.   

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$user = $select
		->column('ID', 'Name')
		->from('User')
		->byField('ID', 34)
		->queryRow();
	
	// $user = ['ID' => 34, 'Name' => 'Bob']