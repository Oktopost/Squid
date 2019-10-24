---------
queryBool
---------

.. code-block:: php

	public function queryBool($expectOne = true): array


.. rubric:: Parameters

* **$expectOne**: *bool* = true
	

.. rubric:: Return

``null`` if the query returned an empty result set, otherwise the first column of the first row, casted to bool.


.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$isLoggedIn = $select
		->column('IsLoggedIn')
		->from('User')
		->where('ID = ?', 34)
		->queryBool();
	
	// $isLoggedIn = true