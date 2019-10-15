-----------
queryColumn
-----------

.. code-block:: php

	public function queryColumn($oneOrNone = true): array


.. rubric:: Parameters

* **$oneOrNone**: *bool* = true
	

.. rubric:: Return


.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$user = $select
		->column('ID')
		->from('User')
		->byField('IsLoggedIn', true)
		->queryColumn();
	
	// $user = ['ID' => 34, 'Name' => 'Bob']