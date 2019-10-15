--------
queryInt
--------

.. code-block:: php

	public function queryInt($expectOne = true): array


.. rubric:: Parameters

* **$expectOne**: *bool* = true
	

.. rubric:: Return

	``false`` if the query returned an empty result set, otherwise the first column of the first row, casted to int.


.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$minID = $select
		->column('ID')
		->from('User')
		->orderBy('ID')
		->limitBy(1)
		->queryInt();
	
	// $minID = 33