----
bind
----

.. code-block:: php
	
	public function bind(): array


.. rubric:: Return
	
| Bind values for this query, in the same order as would be sent to the server.
| Note that his values are not escaped or validated yet so invalid values my be peresent if the object was not setup correctly.


.. rubric:: Examples

