==========
queryFloat
==========

.. code-block:: php

	public function queryBool(?bool $default = null, bool $failOnMultipleResults = true): ?bool

Alias to :ref:`select_queryScalar`, ``$select->queryScalar($failOnMultipleResults)`` - unless the value is ``null`` it will be casted to ``bool``.

----------

.. rubric:: Parameters

* **$failOnMultipleResults**: *bool* = true

    If set to **true** and more than one row is selected, an exception will be thrown.

----------

.. rubric:: Return

``$default`` if the query returned an empty result set, otherwise the first column of the first row, casted to bool.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$isLowRate = $select
		->column('MAX(LoginRate) < 0.3')
		->from('User')
		->queryBool();
	
	// $isLowRate = true