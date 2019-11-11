.. _select_queryValuesGroup:

================
queryValuesGroup
================

.. code-block:: php

	public function queryValuesGroup($key = 0, $value = 1, bool $useMap = false): array|Structura\Map;

Similar to the :ref:`select_queryValuesMap`	method, but instead of mapping a single value to the ``$key`` index,
all values that have the same key are grouped into one array.  

----------

.. rubric:: Parameters

* **$key**: *string* | *int* = 0

	Column index or name to map by. If integer is passed, a numeric array will be selected, 
	in this case column names are ignored.

* **$value**: *string* | *int* = 1

	Value's column name or index.

* **$useMap**: *bool* = false

	If set to true, return an instance of **Structura\Map**, otherwise return an array.

----------

.. rubric:: Return 

**Array** of **Structura\Map** based on the value of ``$useMap``.

| Each element inside the returned set will be an array of values.
| If ``$key`` column's value is **null** it will be cast to 0.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$map = $select
		->column('IsBanned', 'Name')
		->from('User')
		->queryValuesGroup('IsBanned', 'Name');
	
	// $map = [ 
	// 		0 => ['John', 'Bob', ...], 
	// 		1 => ['Daniel', 'Vitalii', ...]
	// ]