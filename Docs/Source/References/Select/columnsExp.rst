----------
columnsExp
----------

.. code-block:: php

	public function columnsExp($columns, $bind = false): static

Add a single expression to the :code:`SELECT` clause. Note that the string is appended as is, meaning that if a comma is 
present in the :code:`$columns` parameter, it will also be present is the query.

----------

.. rubric:: Parameters

* **$columns**: *string|string[]*  

	Single column or array of columns to select. Can be either a column name or any other valid MySQL expression.

* **$bind**:  *mixed* | *array* | *false* 
	
	Array of bind values or a single bind value. Empty array of false will be considered as no bind values. 
	Use ``[false]`` to bind the ``false`` value

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->columnsExp('CONCAT(?, Name)', ['Mr. ']);
	// SELECT CONCAT(?, Name) ...
	// Bind: 'Mr. '
	
	
	$select
		->columnsExp('CONCAT(?, Name)', ['Mr. ']);
		->columnsExp('(?)', [$id]);
	
	// SELECT CONCAT(?, Name), (?) ...
	// Bind: 'Mr. ', $id 