-------
limitBy
-------

.. code-block:: php

	public function limitBy($count): static

Equivalent to calling ``limit`` with an ``$offset`` of 0: ``->limit(0, $count)``.


.. rubric:: Parameters

* **$page**:  *int* 
	
	Zero based index of the page to select

* **$pageSize**:  *int*

	Number of elements per page


.. rubric:: Return
	
Reference to ``$this``

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->page(3, 10);
	// SELECT ... LIMIT 30, 10