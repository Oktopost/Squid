------
column
------

.. code-block:: php
	
	public function column(...$columns): static

List of columns to append to the :code:`SELECT` clause.

----------

.. rubric:: Parameters

* **$columns**: *string[]*  

	Array of columns or valid SQL queries to execute.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->column('a', 'COUNT(*)', 't.Name', '(SELECT 1)');
	// SELECT a,COUNT(*),t.Name,(SELECT 1) ... 
