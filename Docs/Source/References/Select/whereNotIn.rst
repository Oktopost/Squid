----------
whereNotIn
----------

.. code-block:: php
	
	public function whereNotIn($field, $values): static




Generate a ``WHERE NOT IN`` expression. Alias to :ref:`select_whereIn`:

.. code-block:: php
	
	$select->whereIn($field, $values, true);


.. rubric:: Parameters

* **$field**: *string* | *string[]*

	Field or array of fields to compare.
	

* **$values**:


.. rubric:: Return
	
Reference to ``$this``


.. rubric:: Examples

