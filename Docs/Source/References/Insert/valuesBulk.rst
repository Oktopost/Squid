.. _insert_valuesBulk:

==========
valuesBulk
==========

.. code-block:: php
	
	public function valuesBulk(array $valuesSet): static


Calling

.. code-block:: php
	
	$cmd->valuesBulk($set);

is identical to

.. code-block:: php

	foreach ($set as $record)
	{
		$cmd->values($record);
	}

See :ref:`insert_values` method for more information.

----------

.. rubric:: Parameters

* **$valuesSet**: *array*
	
	Array of records. Each record is an asosiative or numeric array of values.
	
----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$insert
		->into('User')
		->valuesBulk([
			[
				'ID'   => 1,
				'Name' => 'Bob'
			],
			[
				'ID'   => 2,
				'Name' => 'Daniel'
			]
		]);
	
	// INSERT INTO `User` (`ID`,`Name`) VALUES (?,?), (?,?) 
	// Bind: [1, "Bob", 2, "Daniel"]