==========
withRollup
==========

.. code-block:: php
	
	public function withRollup($withRollup = true): static

Toggle if ``WITH ROLLUP`` expression should be added to the query.


----------

.. rubric:: Parameters

* **$withRollup**: *bool* = true

	If set to true, ``WITH ROLLUP`` will be added to the expression.

----------


.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:

	$select
		->column('Status', 'COUNT(*)')
		->from('User', 'u')
		->groupBy('Status')
		->withRollup();
	
	// SELECT Status,COUNT(*) FROM User u GROUP BY Status WITH ROLLUP
