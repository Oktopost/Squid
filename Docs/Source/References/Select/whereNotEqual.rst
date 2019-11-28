=============
whereNotEqual
=============

.. code-block:: php
	
	public function whereNotEqual(string $field, $value): static

| Alias to :ref:`select_where`, ``$select->where("$field != ?", $value)``.
| For **null** value, a ``IS NOT NULL`` expression is used. 

----------

.. rubric:: Parameters

* **$field**: *string*
	
	Field name or MySQL expression to compare. 

* **$value**: *mixed*

	Any scalar value to compare with, including **null**.

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
		->whereNotEqual('IsBanned', 1); 

	// SELECT * FROM User WHERE IsBanned != ? 
	// Bind: [1]

If null value is passed, a ``NOT NULL`` expression is used instead.

.. code-block:: php
	:linenos:
	
	$select = $mysql->getConnector()->select();
	
	$select
		->from('User')
		->whereNotEqual('OwnerId', null); 

	// SELECT * FROM User WHERE OwnerId IS NOT NULL 
	// Bind: []
