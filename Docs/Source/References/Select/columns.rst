-------
columns
-------

.. code-block:: php

	public function columns($columns, $table = false): static

Add a single or an array of columns to the :code:`SELECT` clause with an option to attach a table prefix before each column.


.. rubric:: Parameters

* **$columns**: *string[]*  

	Single string or array of strings. Can be either a column name or any other valid MySQL expression.

* **$table**:  *string* | *false* 
	
	If set, will be appended as the table alias before each column.


.. rubric:: Return
	
Reference to ``$this``

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select->columns('a');
	// SELECT a ...
	
	$select->columns('a', 't');
	// SELECT `t`.`a` ...
	
	$select->columns(['a', 'COUNT(*)', 't.b']);
	// SELECT a, COUNT(*), t.b ...

.. note:: 

	| If the parameter ``$table`` is used, all the values spesified in the ``$column`` parameter will be wrapped around with the ````` character.
	| For example, invoking ``$select->columns('COUNT(*)', 'Users');`` will result in ``SELECT `Users`.`COUNT(*)```  