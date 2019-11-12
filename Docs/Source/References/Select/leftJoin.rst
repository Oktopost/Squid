========
leftJoin
========

.. code-block:: php
	
	public function leftJoin($table, string $alias, string $condition, $bind = [], bool $outer = false): static

Add a left join expression to the query.

| Refer to the :ref:`select_join` method for more info. 
| The behaviour of this two methods is identical, excluding the ``LEFT``/``LEFT OUTER`` keywords.

----------

.. rubric:: Parameters

* **$table**: *string* | *IMySqlCommandConstructor*

	Table name, MySQL expression or MySQL command to use as the target. 
	
* **$alias**: *string*
	
	Alias for the table/expression.

* **$condition**: *string*

	Expression to use as the join condition.

* **$bind**: *mixed* | *array* = []
	
	Optional scalar or array of scalar bind parameters.

* **$outer**: *bool* = false

	If set to true, ``LEFT OUTER JOIN`` is generated.

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select
		->from('User', 'u')
		->leftJoin('Account', 'a', 'a.OwnerId = u.Id AND u.Status = ?', ['active'], true);

	// SELECT * FROM User u LEFT OUTER JOIN Account a ON a.OwnerId = u.Id AND u.Status = ? 
	// Bind: ['active']
