.. _select_queryWithCallback:

=================
queryWithCallback
=================

.. code-block:: php

	public function queryWithCallback(callable $callback, ?array &$result = null, bool $isAssoc = true)

Instead of returning the result, pass each row to the provided callback.

``queryWithCallback`` will generally use less memory than any other query command, especially when working with big data sets.
Instead of storing the entire data set in a single array, ``queryWithCallback`` will only allocate enough memory to the currently processed record.


----------	

.. rubric:: Parameters

* **$callback**: *callable*

	The function to call, in format ``function(array $row): bool``
	
	``$callback`` is invoked for each row returned from the result set. The return value of ``queryWithCallback`` 
	depends on the result returned by the callback for the rows. 
	
	* If ``$callback`` returns **false**, the loop will break immediately, **false** is returned, and ``$result`` will not contain any values.
	* If a non scalar value is returned (array or object), it will be appended to the ``$result`` parameter, if it's not null.
	* If ``$callback`` returns the value **0**, the loop will break and return **true**.

* **$result**: *array* | *null* = null

	If set to an array, will contain all non-scalar values returned by ``$callback``. 
	
	If ``$callback`` returns **false**, ``$result`` will be empty.

* **$isAssoc**: *bool* = true
	
	| If set to **true**, an associative array of results will be passed to the callback.
	| If **false**, a numeric array is used.

----------	

.. rubric:: Return

**false**, if ``$callback`` returns **false** for any of the rows. Otherwise **true** will be returned. 


----------	

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select
		->column('ID')
		->from('User')
		->queryWithCallback(function(array $user)
		{
			var_dump($user);
		});

	// ['ID' => 1]
	// ['ID' => 2]
	// ['ID' => 3]
	// ....

Using the ``$result`` parameter

.. code-block:: php
	:linenos:
	
	$result = [];
	
	$select
		->column('Id', 'Name')
		->from('User')
		->queryWithCallback(function (array $data)
		{
			if ($data['Id'] % 2 === 0)
			{
				return $data;
			}
		},
		$result);

	// Returned value is true.
	// 
	// $result = [
	//		['ID' => 0],
	//		['ID' => 2],
	//		['ID' => 4],
	//		....
	// ]

.. code-block:: php
	:linenos:
	
	$result = [];
	
	$select
		->column('Id', 'Name')
		->from('User')
		->queryWithCallback(function (array $data)
		{
			if (!$data['Name'])
			{
				return false;
			}
		},
		$result);

	// Returned value is false.
	// $result = [];
	