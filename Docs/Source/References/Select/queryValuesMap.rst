.. _select_queryValuesMap:

==============
queryValuesMap
==============

.. code-block:: php

	public function queryValuesMap($key = 0, $value = 1, bool $useMap = false): array|Structura\Map;

Query the result and return a map where the value of ``$value`` column is mapped according to the value of the ``$key`` column.   

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

| If ``$key`` column's value is **null** it will be cast to 0. 
| If ``$key`` exists more then once, only the last value will be present in the result.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$map = $select
		->column('Id', 'Name')
		->from('User')
		->queryValuesMap();
	
	// $map = [ 
	// 		1 => 'John', 
	// 		2 => 'Bob', 
	//		... 
	// ]