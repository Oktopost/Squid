-----
limit
-----

.. code-block:: php

	public function limit(int $from, int $count): static

Set the limits of the current query.

----------

.. rubric:: Parameters

* **$from**: *int*  

	Zero based query offset

* **$count**:  *int* 
	
	Maximum number of elements to select

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->limit(10, 2);
	// SELECT ... LIMIT 10, 2