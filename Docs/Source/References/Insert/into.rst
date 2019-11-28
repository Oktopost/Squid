.. _insert_into:

====
into
====

.. code-block:: php
	
	public function byField($field, $value): static


----------

.. rubric:: Parameters

* **$field**: *string*
	
	Name of the field to compare to the ``$value`` parameter. ``$field`` can also be any *safe* MySQL expression. 
	
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