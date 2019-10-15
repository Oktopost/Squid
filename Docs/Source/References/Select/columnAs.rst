--------
columnAs
--------

.. code-block:: php
	
	public function columnAs($column, $alias): static

Equivalent to :code:`->column("$column as $alias")`


.. rubric:: Parameters

* **$column**: *string*  

	Single column or MySQL expression to select.

* **$alias**: *string*  

	Column alias


.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->columnAs('u.Name', 'UserName');
	// SELECT u.Name as UserName ...
