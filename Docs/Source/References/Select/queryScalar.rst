.. _select_queryScalar:

===========
queryScalar
===========

.. code-block:: php

	public function queryScalar($default = null, bool $failOnMultipleResults = true): array

----------

.. rubric:: Parameters

* **$default**: *mixed* = null
	
	Default value to return if the result set is empty.

* **$failOnMultipleResults**: *bool* = true

	If set to ``true`` and more then one column **or** row is selected, throw an exception. 

----------	

.. rubric:: Return

| Depending on the type of the selected column, a scalar value of type ``int``, ``bool``, ``float``, ``string`` or the ``null`` value, may be returned.
| If the result set is empty, than the value of ``$default`` is returned.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$userName = $select
		->column('Name')
		->from('User')
		->byField('ID', 34)
		->queryScalar();
	
	// $user = 'Bob'

----------

.. warning::
	
	No ``LIMIT`` clause is appended to the query when invoking ``queryScalar`` or any of the alias methods.