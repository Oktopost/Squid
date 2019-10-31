=======
groupBy
=======

.. code-block:: php
	
	public function groupBy($column, $bind = []): static

----------

.. rubric:: Parameters

* **$column**: *string* | *string[]*
	
	| A single column or an array of columns to group by.
	| Note that this value is attached to the query as is and therefore should be MySQL safe.
	
* **$bind**: *mixed* | *array* = []

	Optional scalar or array of scalar bind parameters.
	
----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:

	$select
		->column('Status', 'COUNT(*)')
		->from('User', 'u')
		->groupBy('Status');
	
	// SELECT Status,COUNT(*) FROM User u GROUP BY Status