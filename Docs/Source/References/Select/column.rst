------
column
------

List columns to append to the :code:`SELECT` clause.


.. code-block:: php
		:linenos:
	
		public function column(...$columns): static

.. note::

	hello

	.. code-block:: php
		:linenos:
	
		public function column(...$columns): static
		public function column(...$columns): static
		public function column(...$columns): static
		public function column(...$columns): static
		public function column(...$columns): static

	asdas


.. rubric:: Parameters

* **$columns**: *string[]*  

	Array of columns or valid SQL queries to execute.


.. rubric:: Return Value

This function will return a reference to the original select object.


.. rubric:: Examples

.. code-block:: php
	
	$select->column('a', 'COUNT(*)');
	// SELECT a, COUNT(*) ...
