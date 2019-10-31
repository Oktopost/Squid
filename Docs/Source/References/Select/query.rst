=====
query
=====

.. code-block:: php

	public function query(): array

Execute the query and return an array of associative rows.

----------

.. rubric:: Return
	
| Array of rows where each row is an associative array. The key of each column is based on the selected column name or alias, if provided.
| Order of the values in each row will match the order in which the column was specified.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$users = $select
		->columnAs('ID', 'UserID')
		->column('Name')
		->from('User')
		->byField('IsLoggedIn', true)
		->query();

	// $users = [ ['UserID' => 1, 'Name' => 'Bob'], ['UserID' => 2, 'Name' => 'Jen'] ]