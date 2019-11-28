.. _insert_exec:

====
exec
====

.. code-block:: php
	
	public function exec(bool $returnCount = false)


----------

.. rubric:: Parameters

* **$returnCount**: *bool* = true
	
	If set to true, return number of affected rows.
	
----------

.. rubric:: Return
	
| Integer value if ``$returnCount`` set to true. This value will be the number of affected rows as returned by MySQL.
| If ``$returnCount`` is false, **true** will be returned.

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$insertA = $insert
		->into('User')
		->values([
			'ID'   => 1,
			'Name' => 'Bob'
		])
		->exec(true);
	
	$insertB = $insert
		->ignore()
		->into('User')
		->values([
			'ID'   => 1,
			'Name' => 'Bob'
		])
		->exec(true);
	
	$insertC = $insert
		->ignore()
		->into('User')
		->values([
			'ID'   => 1,
			'Name' => 'Bob'
		])
		->exec();

The values will be ``$insertA = 1`` as one record was inserted, ``$insertB = 0`` as no records were inserted due to duplicate 
key and ``$insertC = true`` because even though no records were inserted, the query was still completed successfully.