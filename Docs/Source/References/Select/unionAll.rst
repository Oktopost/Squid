--------
unionAll
--------

.. code-block:: php
	
	public function unionAll(IMySqlCommandConstructor $select): static

Alias to ``$select->union($subQuery, true)``, see the :ref:`select_union` method for more info.

----------

.. rubric:: Parameters

* **$select**: *IMySqlCommandConstructor*

	The command to append to the union section.
	
----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples


.. code-block:: php
	:linenos:
	
	$select1 = $mysql->getConnector()->select();
	$select2 = $mysql->getConnector()->select();
	
	$select1
		->column('Id')
		->from('User')
		->byFields([
			'IsBanned'		=> false,
			'IsLoggedIn'	=> true
		]);
	
	$select2
		->column('0')
		->from('Dual')
		->union($select1);


	// (SELECT 0 FROM Dual ) UNION ALL 
	// (SELECT Id FROM User WHERE IsBanned=? AND IsLoggedIn=? ) 
	// Bind: [false, true] 