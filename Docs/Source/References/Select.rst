Select
======


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


Select clause
-------------

List columns to append to the :code:`SELECT` clause.


.. function:: public function column(...$columns): static

============  =====
**$columns**  Array of columns or valid SQL queries to execute.
============  =====


.. code:: php
	
	$select->column('a', 'COUNT(*)');
	// SELECT a, COUNT(*) ...


2. columns
----------

Add a single or an array of columns to the :code:`SELECT` clause with an option to attach a table prefix before
each column.


.. function:: public function columns($columns, $table = false): static

============ =====
**$columns** Single column or array of columns to select. Can be either a column name or any other valid MySQL expression.
**$table**   If set, will be appended as the table alias before each column.
============ =====


.. code:: php
	
	$select->columns('a');
	// SELECT a ...
	
	$select->columns('a', 't');
	// SELECT `t`.`a` ...
	
	$select->columns(['a', 'COUNT(*)', 't.b']);
	// SELECT a, COUNT(*), t.b ...


3. columnsExp
-------------

Add a single expression to the :code:`SELECT` clause. Note that the string is appended as is, meaning that if a comma is 
present in the :code:`$columns` parameter, it will also be present is the query, however it's strongly recommended to 
append such expressions using multiple calls to **column*** methods.


.. function:: public function columnsExp($columns, $bind = false): static

============ =====
**$columns** Single column or array of columns to select. Can be either a column name or any other valid MySQL expression.
**$bind**    Bind values for the query.
============ =====


.. code:: php

	$select->columnsExp('CONCAT(?, Name)', ['Mr. ']);
	// SELECT CONCAT(?, Name) ...
	// Bind: 'Mr. '
	
	
	$select
		->columnsExp('CONCAT(?, Name)', ['Mr. ']);
		->columnsExp('(?)', [$id]);
	
	// SELECT CONCAT(?, Name), (?) ...
	// Bind: 'Mr. ', $id 


4. columnAs
-----------

.. function:: public function columnAs($column, $alias): static

============ =====
**$column**  Single column or MySQL expression to select.
**$alias**   Must be a safe string.
============ =====

Equivalent to :code:`->column("$column as $alias")`


.. code:: php
	
	$select->columnAs('u.Name', 'UserName');
	// SELECT u.Name as UserName ...


5. columnAsExp
--------------

.. function:: public function columnAsExp($column, $alias, $bind = false): static

============ =====
**$column**  Single column or MySQL expression to select.
**$alias**   Table alias
**$bind**    Bind values for the query.
============ =====

Equivalent to :code:`->columnsExp(["$column as $alias"], $bind)`


.. code:: php
	
	$select->columnAsExp('CONCAT(?, Name)', 'Name', ['Mr. ']);
	// SELECT CONCAT(?, Name) as Name ...
	// Bind: 'Mr. '