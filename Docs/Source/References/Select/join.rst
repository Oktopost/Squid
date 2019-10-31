.. _select_join:

====
join
====

.. code-block:: php
	
	public function join($table, string $alias, string $condition, $bind = []): static

| Add an inner join expression to the query. 
| Table can be a table name or another subquery. 

Note that $alias is not optional and must be provided.

----------

.. rubric:: Parameters

* **$table**: *string* | *IMySqlCommandConstructor*

	Table name, MySQL expression or MySQL command to use as the target. 
	
* **$alias**: *string*
	
	Alias for the table/expression.

* **$condition**: *string*

	Expression to use as the join condition.

* **$bind**: *array* | *mixed* = []
	
	Optional scalar or array of scalar bind parameters.

----------

.. rubric:: Return
	
Reference to ``$this``

----------

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

Therefore ``join`` method should be used when generating a query that selects data from multiple tables.

----------

.. rubric:: Examples

Add a join expression with another table to the query 

.. code-block:: php
	:linenos:
	
	$select
		->from('User', 'u')
		->leftJoin('Account', 'a', 'a.OwnerId = u.Id AND u.Status = ?', ['active']);

	// SELECT * FROM User u JOIN Account a ON a.OwnerId = u.Id AND u.Status = ? 
	// Bind: ['active']

Add a join expression with a sub query 

.. code-block:: php
	:linenos:
	
	$select
		->from('User', 'u')
		->leftJoin('Account', 'a', 'a.OwnerId = u.Id AND u.Status = ?', ['active']);

	// SELECT * 
	// FROM 
	//	Account a JOIN (
	//		SELECT DISTINCT u.Id as id
	//		FROM User u 
	//		WHERE IsBanned=?
	//	) sub_u ON sub_u.id = a.OwnerId
	// 
	// Bind: [true]