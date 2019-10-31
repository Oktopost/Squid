============
whereBetween
============

.. code-block:: php
	
	public function whereBetween(string $field, $greater, $less): static

Alias to :ref:`select_where`, ``$select->where("$field BETWEEN ? AND ?", [$greater, $less])``. 

----------

.. rubric:: Parameters

* **$field**: *string*
	
	Field name or MySQL expression to compare to passed values. 

* **$greater**: *mixed*

	Any scalar value to compare to.

* **$less**: *mixed*

	Any scalar value to compare to.

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
		->whereBetween('Created', '2019-01-01', '2019-02-01'); 

	// SELECT * FROM User WHERE Created BETWEEN ? AND ?
	// Bind: ["2019-01-01", "2019-02-01"]

