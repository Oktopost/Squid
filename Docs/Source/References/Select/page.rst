====
page
====

.. code-block:: php

	public function page($page, $pageSize): static

Append a ``LIMIT`` expression equivalent to selecting the Nth page, given a size of ``$pageSize`` elements per page.

For example, the offset of the 7th page with 6 elements per page is: ``7 * 6 = 42``, therefore ``->page(7, 6)`` 
will generate the expression ``LIMIT 42, 6``.

----------

.. rubric:: Parameters

* **$page**: *int*  

	**Zero** based query offset

* **$pageSize**:  *int* 
	
	Maximum number of elements to select

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select
		->from('Account')
		// ...
		->page(0, 6);
	
	// SELECT ... LIMIT 0,6
	
	$select
		->from('Account')
		// ...
		->page(7, 6);
	
	// SELECT ... LIMIT 42,6

