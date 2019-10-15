-----------
queryScalar
-----------

.. code-block:: php

	public function queryScalar($default = false, $expectOne = true): array


.. rubric:: Parameters

* **$default**: *bool* = false
* **$expectOne**: *bool* = true
	

.. rubric:: Return


.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$userName = $select
		->column('Name')
		->from('User')
		->byField('ID', 34)
		->queryScalar();
	
	// $user = 'Bob'