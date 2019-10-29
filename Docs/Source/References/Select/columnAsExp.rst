-----------
columnAsExp
-----------

.. code-block:: php
	
	public function columnAsExp($column, $alias, $bind = []): static

Equivalent to ``->columnsExp(["$column as $alias"], $bind)``

----------

.. rubric:: Parameters

* **$column**: *string*  

	Single column or MySQL expression to select.

* **$alias**: *string*  

	Column alias

* **$bind**: *array* | *mixed* = []  

	Optional scalar or array of scalar bind parameters.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->columnAsExp('CONCAT(?, Name)', 'Name', ['Mr. ']);
	// SELECT CONCAT(?, Name) as Name ...
	// Bind: 'Mr. '
