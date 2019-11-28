.. _select_union:

=====
union
=====

.. code-block:: php
	
	public function union(IMySqlCommandConstructor $select, bool $all = false): static

Add a union expression to the query.

----------

.. rubric:: Parameters

* **$select**: *IMySqlCommandConstructor*

	The command to append to the union section.

* **$all**: *bool* = false

	If set to true, a ``UNION ALL`` expression is generated.

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples


.. code-block:: php
	:linenos:
	
	$select1 = $mysql->getConnector()->select();
	$select2 = $mysql->getConnector()->select();
	$select3 = $mysql->getConnector()->select();
	
	$select1
		->column('Id')
		->from('User')
		->byFields([
			'IsBanned'		=> false,
			'IsLoggedIn'	=> true
		]);
	
	$select2
		->column('0')
		->from('Dual');
	
	$select3
		->column('Id')
		->from('Account')
		->byField('Disabled', true)
		->union($select1)
		->union($select2);

	// (SELECT Id FROM Account WHERE Disabled=? ) UNION 
	// (SELECT Id FROM User WHERE IsBanned=? AND IsLoggedIn=? ) UNION 
	// (SELECT 0 FROM Dual ) 
	// Bind: [true, false, true]