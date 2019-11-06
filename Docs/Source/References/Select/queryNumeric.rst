============
queryNumeric
============

.. code-block:: php

	public function queryNumeric(): array

Execute the query and return an array of numeric rows.

----------	

.. rubric:: Return
	
| Array of rows where each row is a numeric array. 
| Order of the values in each row will match the order in which the column was specified.  

----------	

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$users = $select
		->column('ID', 'Name')
		->from('User')
		->byField('IsLoggedIn', true)
		->query();
	
	// $users = [ [1, 'Bob'], [2, 'Jen'] ]