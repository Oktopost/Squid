==========
Properties
==========

Properties are additional settings that can affect the behaviour of some methods and commands in squid.


PROP_LIKE_ESCAPE_CHAR
---------------------

.. code:: php

	\Squid\MySql::PROP_LIKE_ESCAPE_CHAR

* **Default**: ``"\"``

| Specify the default escape character to use for the ``LIKE`` statement.
| In MySQL, by default, the ``LIKE`` command does not have an escape character. For more info see https://dev.mysql.com/doc/refman/8.0/en/string-comparison-functions.html#operator_like  


.. _config_PROP_ID_FIELD:

PROP_ID_FIELD
-------------

.. code:: php

	\Squid\MySql::PROP_ID_FIELD

* **Default**: ``"Id"``

Used for the :ref:`select_byId` method as the name of the ID column.

.. code-block:: php
	:linenos:
	
	$connector = MySql::staticConnector(
	[
		'host'	=> 'localhost',
		// ... , 
		'properties' => 
		[
			MySql::PROP_ID_FIELD => '_ID'
		]
	]);

	echo $connector->select()->from('User')->byId('123');
	// SELECT * FROM User WHERE _ID=?  <=> ["123"]
	
Note that ID Field is configured per connection and not per table nor globally. 