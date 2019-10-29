-----------
orderByDesc
-----------

.. code-block:: php

	public function orderByAsc($column): static

Equivalent to :code:`orderBy($column, OrderBy::DESC)`

Add an ``ORDER BY ... DESC`` expression to the current Select statement for the provided column(s).

----------

.. rubric:: Parameters

* **$column**: *string* | *string[]*  

	Column, or array of columns to order by

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->orderByDesc('COUNT(*)');
	// SELECT ... ORDER BY COUNT(*) DESC
	
	$select->orderByDesc(['COUNT(*)', 'Name']);
	// SELECT ... ORDER BY COUNT(*), Name DESC

----------

.. warning:: 
	
	:code:`$column` parameter is treated as an SQL expression, therefore it must be a safe SQL string.