------
column
------

.. code-block:: php
	
	public function column(...$columns): static

List columns to append to the :code:`SELECT` clause.


.. rubric:: Parameters

* **$columns**: *string[]*  

	Array of columns or valid SQL queries to execute.


.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->column('a', 'COUNT(*)');
	// SELECT a, COUNT(*) ...
