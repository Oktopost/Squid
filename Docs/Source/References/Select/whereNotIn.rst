==========
whereNotIn
==========

.. code-block:: php
	
	public function whereNotIn($field, $values): static

Generate a ``WHERE NOT IN`` expression. Alias to :ref:`select_whereIn`, ``$select->whereIn($field, $values, true)``.

----------

.. rubric:: Parameters

* **$field**: *string* | *string[]*

	Field or array of fields to compare. 
	
* **$values**: *mixed* | *Squid\MySql\Command\ICmdSelect* 

	Scalar value or array of scalar values to compare with; or a sub query to generate
	a ``WHERE IN (SELECT ...`` expression.

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
		->whereNotIn('Status', ['valid', 'banned']);

	// SELECT * FROM User WHERE Status NOT IN (?,?) 
	// Bind: ["valid","banned"]