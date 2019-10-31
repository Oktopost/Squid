==========
queryFloat
==========

.. code-block:: php

	public function queryFloat(?int $default = null, bool $failOnMultipleResults = true): ?float

Alias to :ref:`select_queryScalar`, ``$select->queryScalar($failOnMultipleResults)`` - unless the value is ``null`` it will be casted to ``float``.

----------

.. rubric:: Parameters

* **$failOnMultipleResults**: *bool* = true

----------

.. rubric:: Return

``$default`` if the query returned an empty result set, otherwise the first column of the first row, casted to float.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$maxUserLoginRate = $select
		->column('MAX(LoginRate)')
		->from('User')
		->queryFloat();
	
	// $minID = 0.23