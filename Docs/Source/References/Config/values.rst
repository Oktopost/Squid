=============
Config Values
=============


host
----

* **Keys**: *host*
* **Default**: ``""``

The host to connect to.

database
--------

* **Keys**: *db*, *database*, *dbname*
* **Default**: ``""``

Default database name to use. 

If not spesified, any query with a table will have to specified the database or the databes can be set using the ``USE`` 
command: ``$mysql->getConnector()->direct('USE Users')->executeDml();``

password
--------

* **Keys**: *pass*, *password*, *pwd*
* **Default**: ``""``

User's password.

username
--------

* **Keys**: *user*, *username*
* **Default**: ``""``

Username to use.

port
----

* **Keys**: *port*
* **Default**: ``3306``

Port number as an integer value. 

flags
-----

* **Keys**: *flags*, *attribute* 
* **Default**: ``[]``

| Flags is an array of connection options for the PDO driver.
| See https://www.php.net/manual/en/pdo.construct.php for more info

version
-------

* **Keys**: *version* 
* **Default**: ``"5.6"``

Specify the version of MySQL used on the remote server. 

.. note::
 
 	There is no need to specify more then one digit after the dot. 

| This have affects in a few places on the generated MySQL query and may affect some other minor optimizations.
| For example the :ref:`select_whereIn` method will generated a different query with the first parameter is an array of columns.

* For MySQL 5.6 and lower

.. code:: sql
	
	((A = ? AND B = ? AND ... ) OR (A = ? AND B = ? AND ... ) OR ...) 

* For MySQL 5.7 and higher
 
.. code:: sql
	
	(A, B, ...) IN ((?, ?, ...), (?, ?, ...), ...)
	 
This is due to MySQL 5.6 not using index correctly in the later case.

.. note::
 
 	Currently the only differences is between ``<= 5.6`` and ``5.7 <=``.
	
