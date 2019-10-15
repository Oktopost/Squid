-----------
queryExists
-----------

.. code-block:: php

	public function queryExists(): bool|null
	

.. rubric:: Return
	
		
	

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$hasInvalidUsers = $select
		->from('User')
		->byFields([
			'IsBanned'		=> 1,
			'IsLoggedIn'	=> 1
		])
		->queryExists();
	
	// $hasInvalidUsers = false