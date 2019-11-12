.. _select_queryRecordsGroup:

=================
queryRecordsGroup
=================

.. code-block:: php

	public function queryRecordsGroup($key = 0, bool $excludeKey = false, bool $useMap = false): array|Structura\Map;

Query the entire data set and group each record based on the value of the ``$key`` column.

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

| Each element inside the returned set will be an array of records.
| If ``$key`` column's value is **null** it will be cast to 0. 

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$map = $select
		->from('User')
		->where('Age > ?', 25)
		->queryRecordsMap('Age');
	
	// $map = [
	// 		26 => [
	//			['Id' => 123, 'Name' => 'John', ...],
	//			['Id' => 165, 'Name' => 'Alexey', ...],
	//			...
	//		],
	// 		27 =>[
	//			['Id' => 234, 'Name' => 'Daniel', ...],
	//			['Id' => 4123, 'Name' => 'Ivan', ...],
	//			...
	//		],
	//		...
	// ]

Using a numeric index:

.. code-block:: php
	:linenos:
	
	$map = $select
		->column('Age', 'User.*')
		->from('User')
		->where('Age > ?', 25)
		->queryRecordsMap(0, true);
	
	// $map = [
	// 		26 => [
	//			[123, 'John', ...],
	//			[165, 'Alexey', ...],
	//			...
	//		],
	// 		27 =>[
	//			[234, 'Daniel', ...],
	//			[4123, 'Ivan', ...],
	//			...
	//		],
	//		...
	// ]