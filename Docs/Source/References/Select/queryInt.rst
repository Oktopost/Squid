========
queryInt
========

.. code-block:: php

	public function queryInt(?int $default = null, bool $failOnMultipleResults = true): ?int

Alias to :ref:`select_queryScalar`, ``$select->queryScalar($failOnMultipleResults)`` - unless the value is ``null`` it will be casted to an ``int``.

----------

.. rubric:: Parameters

* **$failOnMultipleResults**: *bool* = true

    If set to **true** and more than one row is selected, an exception will be thrown.

----------

.. rubric:: Return

``$default`` if the query returned an empty result set, otherwise the first column of the first row, casted to int.

----------

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