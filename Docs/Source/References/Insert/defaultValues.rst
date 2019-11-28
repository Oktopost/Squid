.. _insert_defaultValues:

=============
defaultValues
=============

.. code-block:: php
	
	public function defaultValues(array $default)

Set the values that should be use when a record passed to :ref:`insert_values` or :ref:`insert_valuesBulk` is missing a column that's
required for this insert command.

See the :ref:`insert_into` method for more information on how the required columns are detected.

----------

.. rubric:: Parameters

* **$default**: *array*
	
	Associative array of column name as key and value as the default column's value. 
	
----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$insert
		->defaultValues(['ID' => null, 'Created' => '1970-01-01 00:00:00', 'Name' => 'unknown'])
		->into('Leads')
		->valuesBulk([
			[
				'ID'	=> 'a23'
			],
			[
				'ID'	=> 'b02',
				'Name'	=> 'Dan'
			]
		]);
	
	// INSERT INTO `Leads` (`ID`,`Created`,`Name`) VALUES (?,?,?), (?,?,?)  
	// Bind: ["a23","1970-01-01 00:00:00","unknown","b02","1970-01-01 00:00:00","Dan"]