-------
orderBy
-------

.. code-block:: php

	public function orderBy($column, $type = OrderBy::ASC): static

Set the order option of the current sql command. Any consecutive call will append a new expression to the :code:`ORDER BY` claus


.. rubric:: Parameters

* **$column**: *string* | *string[]*  

	Column, or array of columns to order by

* **$type**:  *string* 
	
	Is the query will be descending or ascending


.. rubric:: Return
	
Reference to ``$this``


.. rubric:: Examples

The **$type** parameter can be either 0 for ascending order or 1 for descending. However, it's advised to use the 
:code:`Squid\OrderBy` enum class as shown in the example below.

.. code-block:: php
	:linenos:
	
	$select->orderBy('COUNT(*)', OrderBy::DESC);
	// SELECT ... ORDER BY COUNT(*) DESC
	
	$select->orderBy(['COUNT(*)', 'Name'], OrderBy::ASC);
	// SELECT ... ORDER BY COUNT(*), Name

.. note::

	The ``ASC`` keyword is the default option for the ``ORDER BY`` clause, therefore ``ASC`` will not be appended to the ordered value.

.. warning:: 
	
	:code:`$column` parameter is treated as an SQL expression, therefore it must be a safe SQL string.


* :code:`orderByorderByAsc($column)` is equivalent to :code:`orderBy($column, OrderBy::ASC)`
* :code:`orderByorderByDesc($column)` is equivalent to :code:`orderBy($column, OrderBy::DESC)`