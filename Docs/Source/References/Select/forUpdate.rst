.. _select_forUpdate:

=========
forUpdate
=========

.. code-block:: php
	
	public function forUpdate(bool $forUpdate = true): static

Toggle if the ``FOR UPDATE`` expression should be appended to the generated query.

----------

.. rubric:: Parameters

* **$forUpdate**: *bool* = false

	If set to true, add ``FOR UPDATE`` to query.

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select
		->from('User')
		->byField('ID', 10)
		->forUpdate()
	
	// SELECT * FROM User WHERE ID=? FOR UPDATE
	// Bind: [10]