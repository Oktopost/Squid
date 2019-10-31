.. _select_byFields:

========
byFields
========

.. code-block:: php
	
	public function byFields($fields, $values = null): static

Append multiple compare expressions to the WHERE clause either using a numberic array of fields and numberic array of values,
or by using an associative array in format [field name/expression => value] 

----------

.. rubric:: Parameters

* **$fields**: *string[]*
	
	Either numeric or associative array.
	
	| If a numeric array is passed, the length of ``$fields`` and ``$values`` must be the same.
	| In this case, the query is generated using :ref:`select_byfield` method:  
	
	.. code-block:: php
		
		for ($i = 0; $i < count($fields); $i++)
		{
			$this->byField($fields[$i], $values[$i]);
		}
	
	Therefore passing an array as one of the values will result in a ``IN`` expression, and passing ``null`` will result in a ``ISNULL(...)`` expression.
	
	| If an associative array is passed, the value of ``$values`` is ignored. 
	| The query is generated using :ref:`select_byfield` method as follows: 
	
	.. code-block:: php
		
		foreach ($fields as $field => $value)
		{
			$this->byField($fields[$i], $values[$i]);
		}


	Note that ``$fields`` must not be empty.
	
* **$value**: *array* = false

	| Array of values to assign to fields under the same index from ``$fields`` when ``$fields`` is a numeric array.
	| A value can be a scalar or another array of scalars. The later will generate an ``IN`` clause instead of ``=`` 


----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

Using ``byFields`` with an associative array

.. code-block:: php
	:linenos:
	
	$select
		->from('User')
		->byFields([
			'IsLoggedIn'	=> true,
			'Status'		=> ['active', 'new'],
			'Name'			  => null
		]);

	// SELECT * FROM User WHERE IsLoggedIn=? AND Status IN (?,?) AND ISNULL(Name)
	// bind: [true,"active","new"]
	
Using ``byFields`` with a numeric array as set of values

.. code-block:: php
	:linenos:
	
	$select
		->from('User')
		->byFields(
			['IsLoggedIn', 'Status', 'Name'], 
			[true, ['active', 'new'], null]
		);

	// SELECT * FROM User WHERE IsLoggedIn=? AND Status IN (?,?) AND ISNULL(Name) 
	// bind: [true,"active","new"]

Note that both generate the same result.


----------

.. note::
	
	To check if ``$fields`` is a numeric array, the following expression is used
	
	.. code-block:: php
		
		if (key_exists(0, $fields)) ...
