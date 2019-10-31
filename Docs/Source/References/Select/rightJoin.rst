=========
rightJoin
=========

.. code-block:: php
	
	public function rightJoin($table, string $alias, string $condition, $bind = [], bool $outer = false): static

Add a right join expression to the query.

| Refer to the :ref:`select_join` method for more info. 
| The behaviour of this too methods is identical, excluding the excluding the ``RIGHT``/``RIGHT OUTER`` keywords.

----------

.. rubric:: Parameters

* **$table**: *string* | *IMySqlCommandConstructor*

	Table name, MySQL expression or MySQL command to use as the target. 
	
* **$alias**: *string*
	
	Alias for the table/expression.

* **$condition**: *string*

	Expression to use as the join condition.

* **$bind**: *mixed* | *array* = []
	
	Optional scalar or array of bind params.

* **$outer**: *bool* = false

	If set to true, ``RIGHT OUTER JOIN`` is generated.

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select
		->from('User', 'u')
		->rightJoin('Account', 'a', 'a.OwnerId = u.Id AND u.Status = ?', ['active']);

	// SELECT * FROM User u RIGHT JOIN Account a ON a.OwnerId = u.Id AND u.Status = ? 
	// Bind: ['active']
