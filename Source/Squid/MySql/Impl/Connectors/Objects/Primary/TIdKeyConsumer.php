<?php
namespace Squid\MySql\Impl\Connectors\Objects\Primary;


use Squid\MySql\Impl\Connectors\Objects\Identity\TPrimaryKeysConsumer;


trait TIdKeyConsumer
{
	use TPrimaryKeysConsumer;
	
	
	protected abstract function getIdKey(): array;
	
	
	protected function getPrimaryKeys(): array
	{
		return $this->getIdKey();
	}
}