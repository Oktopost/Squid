---------------
lockInShareMode
---------------

.. code-block:: php
	
	public function lockInShareMode(bool $lockInShareMode = true): static

Toggle if the ``LOCK IN SHARE MODE`` flag should be appended to the query.

----------

.. rubric:: Parameters

* **$lockInShareMode**: *bool* = true

	Set to ``true`` to append the flag.

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples
	
.. code-block:: php
	:linenos:
	
	$select 
		->from('Account', 'a')
		->byField('Id', '100')
		->lockInShareMode()
	
	// SELECT * FROM Account a WHERE Id=? LOCK IN SHARE MODE 
	// Bind: [100]