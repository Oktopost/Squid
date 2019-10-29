----------
orderByAsc
----------

.. code-block:: php

	public function orderByAsc($column): static

Alias to :code:`orderBy($column, OrderBy::ASC)`

Add an ``ORDER BY ... ASC`` expression to the current Select statement for the provided column(s).

----------

.. rubric:: Parameters

* **$column**: *string* | *string[]*  

	Column, or array of columns to order by

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

The ``ASC`` keyword is the default option for the ``ORDER BY`` clause, therefore ``ASC`` will not be appended to the ordered value.

.. code-block:: php
	:linenos:
	
	$select->orderByAsc('COUNT(*)');
	// SELECT ... ORDER BY COUNT(*)
	
	$select->orderBy(['COUNT(*)', 'Name']);
	// SELECT ... ORDER BY COUNT(*), Name

----------

.. warning:: 
	
	``$column`` parameter is treated as an SQL expression, therefore it must be a safe SQL string.