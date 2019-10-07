column
------
 
----------
 
List columns to append to the :code:`SELECT` clause.

.. code-block:: php

	public function column(...$columns): static


Parameters
~~~~~~~~~~ 
 
----------
 
* **$columns**: *string[]*  

	Array of columns or valid SQL queries to execute.


Return Value
~~~~~~~~~~~~
 
----------
 
Examples
~~~~~~~~
 
----------
 
.. code-block:: php
	
	$select->column('a', 'COUNT(*)');
	// SELECT a, COUNT(*) ...

