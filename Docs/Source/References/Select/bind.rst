----
bind
----

.. code-block:: php
	
	public function bind(): array

----------

.. rubric:: Return
	
| Array of bind values for this query, in the same order as would be sent to the server.
| Note that these values are not escaped or validated yet.

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
	
	var_dump($mysql->getConnector()
		->select()
		->column('Id', 'Name')
		->from('User')
		->byField('LoggedIn', true)
		->where('Created < ?', '2019-01-01 00:00:00')
		->bind()); 
	
	// [true, '2019-01-01 00:00:00']