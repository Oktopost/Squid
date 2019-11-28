.. _select_whereIn:

=======
whereIn
=======

.. code-block:: php
	
	public function whereIn($field, $values, $negate = false): static

Generate a ``WHERE A IN ()`` expression. The generated string can be different based on the type of ``$field`` and ``$values`` parameters.  

----------

.. rubric:: Parameters

* **$field**: *string* | *string[]* 

	Single field to search, or array of fields.

* **$values**: *mixed* | *Squid\MySql\Command\ICmdSelect* 

	| Scalar value, array of values or an instance of ``ICmdSelect`` to use as sub query.
	| A Select command in Squid extends the ``ICmdSelect`` interface and therefore can be passed to this method.

	If ``$fields`` is an **array**, ``$values`` parameter must be a sub query that selected the same number of fields **OR**
	an array where each record is an array containing the same number of elements as number of fields. See examples for implementation.

* **$negate**: *bool* = false

	If set to **true**, generate a ``NOT IN`` expression instead.

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
		->whereIn('Status', ['valid', 'banned']);

	// SELECT * FROM User WHERE Status IN (?,?)
	// Bind: ["valid","banned"]

It's possible to pass a subquery as shown in the example below:

.. code-block:: php
	:linenos:
	
	$subQuery = $mysql->getConnector()->select();
	
	$subQuery 
		->distinct()
		->column('UserId')
		->from('AdminList')
		->byField('Deleted', false);

	$select = $mysql->getConnector()->select();
	
	$select
		->from('User')
		->whereIn('Id', $subQuery); 

	// SELECT * FROM User WHERE Id IN (SELECT DISTINCT UserId FROM AdminList WHERE Deleted=? )  
	// Bind: [false]

When comparing a set of fields, the ``$value`` parameter must be array of sets where each size equals to number of compared fields:  

.. code-block:: php
	:linenos:
	
	$select = $mysql->getConnector()->select();
	
	$select
		->from('User')
		->whereIn(
			['Status', 'State'], 
			[
				['valid', 'active'],
				['invalid', 'inactive']
			]
		);

	// SELECT * FROM User WHERE ((Status = ? AND State = ?) OR (Status = ? AND State = ?)) 
	// Bind: ["valid", "active", "invalid", "inactive"]

.. note:: 

	If connector's version is set to 5.7 or greater, the generated query will be different:

	.. code-block:: php
		:linenos:
		
		$select = $mysql->getConnector()->select();
		
		$select
			->from('User')
			->whereIn(
				['Status', 'State'], 
				[
					['valid', 'active'],
					['invalid', 'inactive']
				]
			);
	
		// SELECT * FROM User WHERE (Status,State) IN ((?,?),(?,?))
		// Bind: ["valid", "active", "invalid", "inactive"]

	The reason behind this is how MySQL 5.6 uses indexes.
	
	| Even if an index on ``Status, State`` exists, the ``IN`` query would not use this index, which can result in bad performance. However ``AND OR`` statement would use such index. 
	| This is not the case in MySQL 5.7 and greater. 

	Version can be set using the :ref:`config_version` property.
	