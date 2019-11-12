.. _select_queryRecordsMap:

===============
queryRecordsMap
===============

.. code-block:: php

	public function queryRecordsMap($key = 0, bool $excludeKey = false, bool $useMap = false): array|Structura\Map;

Query the entire data set and return an array where each record is mapped to the value of the ``$key`` column.

----------

.. rubric:: Parameters

* **$key**: *string* | *int* = 0

	Column index or name to map by. If integer is passed, a numeric array will be selected, 
	in this case column names are ignored.

* **$excludeKey**: *bool* = false

	If set to true, the ``$key`` column will be removed from the record.

* **$useMap**: *bool* = false

	If set to true, return an instance of **Structura\Map**, otherwise return an array.

----------

.. rubric:: Return 

**Array** of **Structura\Map** based on the value of ``$useMap``.

| If ``$key`` column's value is **null** it will be cast to 0. 
| If ``$key`` exists more then once, only the last record will be present in the result.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$map = $select
		->from('User')
		->where('Age > ?', 25)
		->queryRecordsMap('Id');
	
	// $map = [
	// 		123 => ['Id' => 123, 'Name' => 'John', ...], 
	// 		125 => ['Id' => 125, 'Name' => 'Bob', ...], 
	//		... 
	// ]

Using a numeric index:

.. code-block:: php
	:linenos:
	
	$map = $select
		->from('User')
		->where('Age > ?', 25)
		->queryRecordsMap(0);
	
	// $map = [
	// 		123 => [123, 'John', ...], 
	// 		125 => [125, 'Bob', ...], 
	//		... 
	// ]