.. _select_from:

====
from
====

.. code-block:: php
	
	public function from($table, ?string $alias = null): static


Set the expression to put in the ``FROM`` clause. 

----------

.. rubric:: Parameters

* **$table**: *string* | *IMySqlCommandConstructor*
	
	Table name to select or any valid, *safe* MySQL expression that can be placed in the ``FROM`` clause.

	| Additionally, any instance that implements the ``IMySqlCommandConstructor`` interface may be passed. 
	| In this case, the value of ``$table->assemble()`` is used as the FROM expression, and ``$table->bind()`` is added to the bind params 
	  of the current query.
	

* **$alias**: *string* | *null* = null
	
	Table alias to use. 

----------

.. rubric:: Return
	
Reference to ``$this``

----------

Unlike other methods in ``ICmdSelect``, if ``from`` method is called more than once, the new value will override existing value.

.. code-block:: php
	:linenos:
	
	$select
		->from('User u')
		->from('Account', 'a');

	// SELECT * FROM Account a ...


To generate a SELECT from multiple tables, use the :ref:`select_join` method.

.. code-block:: php
	:linenos:
	
	$select
		->from('User u')
		->join('Account', 'a', 'u.OwnerId = a.Id');
	
	// SELECT FROM User u JOIN Account a ON u.OwnerId = a.Id

Note that in MySQL listing tables in the following way - ``A, B`` in the FROM clause is a short version of using inner join.

For example:

.. code-block:: sql
	:linenos:

	SELECT *
	FROM 
		User,
		Account
	WHERE
		User.OwnerId = Account.Id

Is equal to 

.. code-block:: sql
	:linenos:

	SELECT *
	FROM 
		User JOIN Account ON User.OwnerId = Account.Id

Therefore any ``FROM a, b, ...`` can be replaced with ``FROM a JOIN b ON ... JOIN c ON ...``.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:

	$select
		->from('User');
	// SELECT * FROM User ...

	$select
		->from('User u');
	// SELECT * FROM User u ...

	$select
		->from('User', 'u');
	// SELECT * FROM User u ...

Using another ``SELECT`` command as the ``FROM`` expression

.. code-block:: php
	:linenos:
	
	$subSelect = $mysql->getConnector()->select()
		->distinct()
		->columnAs('Status', 'st')
		->from('User', 'u')
		->byField('IsBanned', true);

	$select = $mysql->getConnector()->select()
		->from($subSelect, 'sub_u')
		->byField('sub_u.st', ['active', 'deleted'])

	// SELECT * 
	// FROM 
	//	(SELECT DISTINCT Status as st FROM User u WHERE IsBanned=?) sub_u
	// WHERE sub_u.st IN (?,?)
	// 
	// Bind: [true,"active","deleted"]