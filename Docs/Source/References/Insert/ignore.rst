.. _insert_ignore:

======
ignore
======

.. code-block:: php
	
	public function ignore(bool $ignore = true)

Execute the insert command as ``INSERT IGNORE INTO ...``

When the ``IGNORE`` command is present, any duplicate key errors are ignored and the duplicate values will not update inserted 
or updated. However any other type of error, will not be ignore. This is MySQL's behaviour and not controller by Squid.

----------

.. rubric:: Parameters

* **$ignore**: *bool* = true
	
	Specify if the ``IGNORE`` keyword command should be present. 
	
----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$insert
		->ignore()
		->into('User')
		->values([
			'ID'   => 1,
			'Name' => 'Bob'
		]);
	
	// INSERT IGNORE INTO `User` (`ID`,`Name`) VALUES (?,?)  
	// Bind: [1, "Bob"]