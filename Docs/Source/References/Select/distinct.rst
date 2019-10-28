========
distinct
========

.. code-block:: php
	
	public function distinct($distinct = true): static

Toggle if the ``DISTINCT`` keyword should be added to the generated query.

----------

.. rubric:: Parameters

* **$distinct**: *bool* = true
	
	| If set to true, add the ``DISTINCT`` keyword to the generated query.
	| By default ``DISTINCT`` is not present.

----------

.. rubric:: Return

Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select
		->distinct()
		->column('Name')
		->from('User')
	
	// SELECT DISTINCT Name FROM User