========
leftJoin
========

.. code-block:: php
	
	public function leftJoin($table, $alias, $condition, $bind = false, $outer = false): static

Add a left join expression to the query.

| Refer to the :ref:`select_join` method for more info. 
| The behaviour of this too methods is identical, excluding the excluding the ``LEFT``/``LEFT OUTER`` keywords.

----------

.. rubric:: Parameters

* **$table**: *string* | *IMySqlCommandConstructor*

	Table name, MySQL expression or MySQL command to use as the target. 
	
* **$alias**: 
	
	Alias for the table/expression.

* **$condition**: 

	Expression to use as the join condition.

* **$bind**:
	
	Optional scalar or array of bind params. To pass ``false`` as a bind param, use an array - ``[false]``

* **$outer**:

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
