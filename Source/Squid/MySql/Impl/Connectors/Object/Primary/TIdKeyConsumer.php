<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary;


use Squid\MySql\Impl\Connectors\Object\Identity\TPrimaryKeysConsumer;


trait TIdKeyConsumer
{
	use TPrimaryKeysConsumer;
	
	
	protected abstract function getIdKey(): array;
	
	
	protected function getPrimaryKeys(): array
	{
		return $this->getIdKey();
	}
}