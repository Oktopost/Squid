-----------
columnAsExp
-----------

.. code-block:: php
	
	public function columnAsExp($column, $alias, $bind = false): static

Equivalent to :code:`->columnsExp(["$column as $alias"], $bind)`


.. rubric:: Parameters

* **$column**: *string*  

	Single column or MySQL expression to select.

* **$alias**: *string*  

	Column alias

* **$bind**: *string*  

	Bind values for the query


.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->columnAsExp('CONCAT(?, Name)', 'Name', ['Mr. ']);
	// SELECT CONCAT(?, Name) as Name ...
	// Bind: 'Mr. '
