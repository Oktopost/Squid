.. _select_whereExists:

===========
whereExists
===========

.. code-block:: php
	
	public function whereExists(ICmdSelect $select, $negate = false): static

Add an ``EXISTS /*sub query*/`` expression to the where claus. 

----------

.. rubric:: Parameters

* **$select**: *Squid\MySql\Command\ICmdSelect*

	| The subquery to compare with.
 	| A Select command in squid, extends the ``ICmdSelect`` interface and therefor can be passed to this method.

* **$negate**: *bool* = false

	| If set to **true**, generate a ``NOT EXISTS`` expression instead.

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$subQuery = $mysql->getConnector()->select();
	
	$subQuery 
		->from('AdminList', 'al')
		->byField('al.Deleted', false)
		->where('al.UserId = u.Id');

	$select = $mysql->getConnector()->select();
	
	$select
		->from('User', 'u')
		->byField('Status', 'active')
		->whereExists($subQuery); 

	// SELECT * FROM User u WHERE Status=? AND EXISTS (SELECT * FROM AdminList al WHERE al.Deleted=? AND al.UserId = u.Id ) 
	// Bind: ['active', false]