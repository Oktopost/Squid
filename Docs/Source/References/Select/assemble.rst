.. _select_assemble:

========
assemble
========

.. code-block:: php
	
	public function assemble(): string

----------

.. rubric:: Return
	
Generated SQL query.

| ``assemble`` is used by Squid to generate the query that will be sent to MySQL server on execution.
| Excluding some methods like ``queryCount`` and ``queryExists``, any other execution will use exactly the same query. 

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$mysql = new MySql();
	$mysql->addConnector(
		[
			'host'	=> 'localhost',
			'user'	=> 'admin',
			'pass'	=> 'pass',
			'db'	=> 'application'
		]);
	
	echo $mysql->getConnector()
		->select()
		->column('Id', 'Name')
		->from('User')
		->byField('LoggedIn', true)
		->assemble(); 
	
	// SELECT Id,Name FROM User WHERE LoggedIn=?
	

