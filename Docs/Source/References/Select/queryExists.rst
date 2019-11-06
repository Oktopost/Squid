===========
queryExists
===========

.. code-block:: php

	public function queryExists(): bool|null
	
Execute a ``SELECT EXISTS($select)`` on the command. 

.. rubric:: Return
	
True if the sub query contains at least one record.

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
	
	// SELECT EXISTS (SELECT * FROM User WHERE IsBanned=? AND IsLoggedIn=?), [1, 1] 
	// $hasInvalidUsers = false