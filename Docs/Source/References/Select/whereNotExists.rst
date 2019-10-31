==============
whereNotExists
==============

.. code-block:: php
	
	public function whereNotExists(ICmdSelect $select): static

Alias to :ref:`select_whereExists`, ``$select->whereExists($select, true)``. 

----------

.. rubric:: Parameters

* **$select**: *Squid\MySql\Command\ICmdSelect*

	Sub query to compare with.