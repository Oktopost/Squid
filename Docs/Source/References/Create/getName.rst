.. _create_getName:

========
getName
========

.. code-block:: php

	public function getName()


@TODO: COMPLETE

----------

.. rubric:: Parameters

* **$select**: *Squid\MySql\Command\ICmdSelect*

	``SELECT`` subquery to use in the ``INSERT`` statement.

----------

.. rubric:: Return

Reference to ``$this``

----------

.. rubric:: Examples

.. code-block:: php
	:linenos:

	$insert
		->into('BannedUser', ['ID', 'Name'])
		->asSelect(
			$select
				->column('ID', 'Name')
				->from('User')
				->byField('IsBanned', true)
		);

	// INSERT INTO `BannedUser` (`ID`,`Name`) SELECT ID,Name FROM User WHERE IsBanned=?
	// Bind: [true]