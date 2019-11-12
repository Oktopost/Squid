-------
limitBy
-------

.. code-block:: php

	public function limitBy($count): static

Alias to ``->limit(0, $count)``.

----------

.. rubric:: Parameters

* **$count**:  *int*

	Number of elements to select

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->limitBy(10);
	// SELECT ... LIMIT 0, 10