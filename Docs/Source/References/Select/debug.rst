-----
debug
-----

.. code-block:: php
	
	public function debug(): string

.. note:: 

	This method is not part of ``Squid\MySql\Command\ICmdSelect`` but ``Squid\MySql\Impl\Command\CmdSelect``


.. rubric:: Return
	
An array containing the result of ``assemble()`` as the first value and ``bind()`` as teh second:

.. code-block:: php

	public function debug(): array
	{
		return [
			$this->assemble(),
			$this->bind()
		];
	}


.. rubric:: Examples

.. code-block:: php
	
	[$command, $bind] = $cmd->debug();
	
	echo 'Running command ' . $command . ' with ' . implode(', ', $bind));