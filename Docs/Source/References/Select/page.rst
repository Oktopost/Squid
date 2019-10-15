----
page
----

.. code-block:: php

	public function page($page, $pageSize): static

Given a const of :code:`$pageSize` elements per page, match the Nth (:code:`$page`) page for given command.


.. rubric:: Parameters

* **$from**: *int*  

	Zero based query offset

* **$count**:  *int* 
	
	Maximum number of elements to select


.. rubric:: Return
	
Reference to ``$this``

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->limit(10, 2);
	// SELECT ... LIMIT 10, 2