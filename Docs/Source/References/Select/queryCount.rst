----------
queryCount
----------

.. code-block:: php

	public function queryCount(): int|null
	

.. rubric:: Return
	
		
	

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$totalLoggedInUsers = $select
		->from('User')
		->byFields([
			'IsLoggedIn'	=> 1
		])
		->queryCount();
	
	// $totalLoggedInUsers = 738