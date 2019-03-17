Limit Trait
===========


The Limit trait is present in any sql commands that have the :code:`LIMIT` and :code:`ORDER BY` clauses.

* All of the methods, excluding odrerBy's first parameter are binded or otherwise validated and therefore SQLi safe. 
* Calling one of the limit (:code:`limit`, :code:`limitBy` or :code:`page`) methods will override any previously set values.


1. limit
--------

The limit method is equivalent to MySQL's :code:`LIMIT` clause.

.. function:: public limit(int $from, int $count): static


==========  =====
**$from**   Query offset
**$count**  Maximum number of elements to select
==========  =====


.. code-block:: php
	
	$select->limit(10, 2);
	// SELECT ... LIMIT 10, 2


2. limitBy
----------

Equivalent to :code:`limit(0, $count)`.

.. function:: public limitBy($count): static


==========  =====
**$count**  Maximum number of elements to select 
==========  =====


.. code:: php
	
	$select->limitBy(2);
	// SELECT ... LIMIT 2


3. page
-------

Given a const of :code:`$pageSize` elements per page, match the Nth (:code:`$page`) page for given command.

.. function:: public page($page, $pageSize): static


=============  =====
**$page**      Zero based index of the page to select
**$pageSize**  Number of elements per page
=============  =====

.. code:: php
	
	$select->page(3, 10);
	// SELECT ... LIMIT 30, 10


4. orderBy
----------

Set the order option of the current sql command. Any consecutive call will append a new expression to the :code:`ORDER BY` claus.

.. function:: public orderBy($column, $type = OrderBy::ASC)


=============  =====
**$column**    Column, or array of columns to order by
**$type**      Is the query will be descending or ascending
=============  =====


The **$type** parameter can be either 0 for ascending order or 1 for descending. However, it's advised to use the 
:code:`Squid\OrderBy` enum class as shown in the example below.


.. code:: php
	
	$select->orderBy('COUNT(*)', OrderBy::DESC);
	// SELECT ... ORDER BY COUNT(*) DESC
	
	$select->orderBy(['COUNT(*)', 'Name'], OrderBy::ASC);
	// SELECT ... ORDER BY COUNT(*), Name


.. warning:: 
	
	:code:`$column` parameter is treated as an SQL expression, therefore it must be a safe SQL string.