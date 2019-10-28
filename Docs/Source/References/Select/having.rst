======
having
======

.. code-block:: php
	
	public function having($exp, $bind = false): static

The behaviour of this method is identical to :ref:`select_where`, expect that all expressions are appended to the ``HAVING`` clause.

----------

.. rubric:: Parameters

* **$exp**: *string*

	| A *safe* MySQL expression to append to the query.
	| This string is not validated and passed as is to the MySQL server, therefore and *unsafe* values should be passed in the 
	  ``$bind`` parameter.
	
* **$bind**: *mixed* | *array* | *false* 

	Optional bind parameters. To pass ``false`` as a parameter use an array - ``[false]``.

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select
		->column('Status', 'COUNT(*)')
		->from('User')
		->groupBy('Status')
		->having('COUNT(*) > ?', 1)

	// SELECT Status,COUNT(*) FROM User GROUP BY Status HAVING COUNT(*) > ?
	// Bind: [1]