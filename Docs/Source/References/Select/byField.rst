.. _select_byField:

=======
byField
=======

.. code-block:: php
	
	public function byField($field, $value): static

| Add an expression to the WHERE clause that compares ``$field`` with ``$value``. 
| Compare expression used is depended on the type of ``$value``.

----------

.. rubric:: Parameters

* **$field**: *string*
	
	Name of the field to compare to the ``$value`` parameter. ``$field`` can also be any *safe* MySQL expression. 

* **$value**: *mixed* | *array* | *null*

	| If a scalar value is passed, it will be used as a bind value for the generated query. 
	| If an array passed, an ``IN`` expression is generated instead of ``=``. Array must not be empty and contain only
	  scalar values. All the values in this array are used as bind params and therefore *safe*.
	| Passing ``null`` will generate a ``ISNULL($field)`` expression. 
	

----------

.. rubric:: Return
	
Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:
	
	$select
		->from('User')
		->byField('IsLoggedIn', true)
		->byField('Status', ['active', 'new'])
		->byField('Name', null);
	
	// SELECT * FROM User WHERE IsLoggedIn=? AND Status IN (?,?) AND ISNULL(Name)
	// Bind: [true,"active","new"]