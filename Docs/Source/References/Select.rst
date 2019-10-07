Select
======


.. toctree::
	
	Select/column


.. code-block:: php

	$mysql = new MySql();
	$mysql->config()
		->addConfig('main', 
		[
			// ...
		]);
	
	
	$connector = $mysql->getConnector('main');
	
	$select = $connector->select()
		->from('Users')
		->byField('Status', 'active')
		->orderBy('Name')
		->limitBy(10)
		->query();


column
------

.. function:: public function column(...$columns): static

	List columns to append to the :code:`SELECT` clause.


* **$columns**: *string[]* 

	Array of columns or valid SQL queries to execute.


*Example:*

.. code-block:: php
	
	$select->column('a', 'COUNT(*)');
	// SELECT a, COUNT(*) ...


columns
-------

.. function:: public function columns($columns, $table = false): static

	Add a single or an array of columns to the :code:`SELECT` clause with an option to attach a table prefix before each column.


* **$columns**: *string* | *string[]* 

	Single string or array of strings. Can be either a column name or any other valid MySQL expression.

* **$table**:  *string* | *false* 
	
	If set, will be appended as the table alias before each column.


.. code-block:: php
	
	$select->columns('a');
	// SELECT a ...
	
	$select->columns('a', 't');
	// SELECT `t`.`a` ...
	
	$select->columns(['a', 'COUNT(*)', 't.b']);
	// SELECT a, COUNT(*), t.b ...


columnsExp
----------

Add a single expression to the :code:`SELECT` clause. Note that the string is appended as is, meaning that if a comma is 
present in the :code:`$columns` parameter, it will also be present is the query.


.. function:: public function columnsExp($columns, $bind = false): static

============ =====
**$columns** Single column or array of columns to select. Can be either a column name or any other valid MySQL expression.
**$bind**    Bind values for the query.
============ =====


.. code-block:: php

	$select->columnsExp('CONCAT(?, Name)', ['Mr. ']);
	// SELECT CONCAT(?, Name) ...
	// Bind: 'Mr. '
	
	
	$select
		->columnsExp('CONCAT(?, Name)', ['Mr. ']);
		->columnsExp('(?)', [$id]);
	
	// SELECT CONCAT(?, Name), (?) ...
	// Bind: 'Mr. ', $id 


columnAs
--------

.. function:: public function columnAs($column, $alias): static

============ =====
**$column**  Single column or MySQL expression to select.
**$alias**   Must be a safe string.
============ =====

Equivalent to :code:`->column("$column as $alias")`


.. code-block:: php
	
	$select->columnAs('u.Name', 'UserName');
	// SELECT u.Name as UserName ...


columnAsExp
-----------

.. function:: public function columnAsExp($column, $alias, $bind = false): static

============ =====
**$column**  Single column or MySQL expression to select.
**$alias**   Table alias
**$bind**    Bind values for the query.
============ =====

Equivalent to :code:`->columnsExp(["$column as $alias"], $bind)`


.. code-block:: php
	
	$select->columnAsExp('CONCAT(?, Name)', 'Name', ['Mr. ']);
	// SELECT CONCAT(?, Name) as Name ...
	// Bind: 'Mr. '


orderBy
-------

Set the order option of the current sql command. Any consecutive call will append a new expression to the :code:`ORDER BY` claus


.. function:: public function orderBy($column, $type = OrderBy::ASC)

=============  =====
**$column**    Column, or array of columns to order by
**$type**      Is the query will be descending or ascending
=============  =====


The **$type** parameter can be either 0 for ascending order or 1 for descending. However, it's advised to use the 
:code:`Squid\OrderBy` enum class as shown in the example below.


.. code-block:: php
	
	$select->orderBy('COUNT(*)', OrderBy::DESC);
	// SELECT ... ORDER BY COUNT(*) DESC
	
	$select->orderBy(['COUNT(*)', 'Name'], OrderBy::ASC);
	// SELECT ... ORDER BY COUNT(*), Name


.. warning:: 
	
	:code:`$column` parameter is treated as an SQL expression, therefore it must be a safe SQL string.


* :code:`orderByorderByAsc($column)` is equivalent to :code:`orderBy($column, OrderBy::ASC)`
* :code:`orderByorderByDesc($column)` is equivalent to :code:`orderBy($column, OrderBy::DESC)`


limit
-----

The limit method is equivalent to MySQL's :code:`LIMIT` clause.


.. function:: public function limit(int $from, int $count): static

==========  =====
**$from**   Query offset
**$count**  Maximum number of elements to select
==========  =====


.. code-block:: php
	
	$select->limit(10, 2);
	// SELECT ... LIMIT 10, 2


limitBy
-------

Equivalent to :code:`LIMIT BY 0, $count`.

.. function:: public function limitBy($count): static

==========  =====
**$count**  Maximum number of elements to select 
==========  =====


.. code-block:: php
	
	$select->limitBy(2);
	// SELECT ... LIMIT 2


page
----

Given a const of :code:`$pageSize` elements per page, match the Nth (:code:`$page`) page for given command.


.. function:: public function page($page, $pageSize): static

=============  =====
**$page**      Zero based index of the page to select
**$pageSize**  Number of elements per page
=============  =====

.. code-block:: php
	
	$select->page(3, 10);
	// SELECT ... LIMIT 30, 10