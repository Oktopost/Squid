.. _select_where:

=====
where
=====

.. code-block:: php
	
	public function where(string $exp, $bind = []): static

Attach a condition to the WHERE clause. 

The condition is attached as is, without any ``()``, and all conditions in the WHERE clause are combined using ``AND``.

| Any other command that adds conditions to the WHERE clause eventually calls the ``where()`` method.
| For example ``->byField('a', 'b')`` equals ``->where('a = ?', ['b'])``.

----------

.. rubric:: Parameters

* **$exp**:
	
	The sql expression to use. 

.. warning::

	$exp must be a **safe** sql string.  

* **$bind**:

	Optional array of bind parameters.

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
		->where('IsBanned != IsLoggedIn')
		->where('Role', 1)
		->where('(Id = ? OR AccountId = ?)', [0, 0]); 

	// SELECT * FROM User WHERE 
	//	IsBanned != IsLoggedIn AND 
	//	Role = ? AND 
	//	(Id = ? OR AccountId = ?)   
	//
	// Bind: [1,0,0]