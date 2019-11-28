=========
whereLess
=========

.. code-block:: php
	
	public function whereLess(string $field, $value): static

Alias to :ref:`select_where`, ``$select->where("$field < ?", $value)``.

----------

.. rubric:: Parameters

* **$field**: *string*
	
	Field name or MySQL expression to compare. 

* **$value**: *mixed*

	Any scalar value to compare with.

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select = $mysql->getConnector()->select();
	
	$select
		->from('User')
		->whereLess('Created', '2019-01-01'); 

	// SELECT * FROM User WHERE Created < ? 
	// Bind: ["2019-01-01"]