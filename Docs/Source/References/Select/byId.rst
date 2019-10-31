.. _select_byId:

====
byId
====

.. code-block:: php
	
	public function byId($value): static

Alias to ``$select->byField(/* ID Column Name */, $value);`` where column
name is by default ``Id``, unless configured otherwise (See examples).

----------

.. rubric:: Parameters

* **$value**: *mixed* | *array*

	| If a scalar value is passed, it will be used as a bind value for the generated query. 
	| If an array passed, an ``IN`` expression is generated instead of ``=``. Array must not be empty, and contain only 
	  scalar values. All the values in this array are used as bind params and therefore *safe*.
	| Passing ``null`` will generate a ``ISNULL(column)`` expression. 
	

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$connector = MySql::staticConnector([ /* ... */ ]);
	
	$select
		->from('User')
		->byId([1, 2, 3]);
	
	// SELECT * FROM User WHERE Id IN (?,?,?)
	// Bind: [1, 2, 3]

By default, ID Column name is set to ``Id``. However it's possible to change the default ID column by setting the  
:ref:`config_PROP_ID_FIELD` property to a different value. 

.. code-block:: php
	:linenos:
	
	$connector = MySql::staticConnector(
		[
			'host'	=> 'localhost',
			// ... , 
			'properties' => 
			[
				MySql::PROP_ID_FIELD => '_ID'
			]
		]);

	$select
		->from('User')
		->byId([1, 2, 3]);
	
	// SELECT * FROM User WHERE _ID IN (?,?,?)
	// Bind: [1, 2, 3]
	