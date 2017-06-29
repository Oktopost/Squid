<?php
namespace Squid\MySql\Impl\Connectors\Object\Generic;


use Squid\MySql\Connectors\Object\Generic\IGenericIdConnector;

use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Impl\Connectors\Object\Primary\TIdKey;
use Squid\MySql\Impl\Connectors\Object\Primary\TIdComposition;


class GenericIdConnector extends GenericObjectConnector implements IGenericIdConnector
{
	use TIdComposition;
	use TIdKey;
	
	
	protected function getPrimaryKeys(): array
	{
		return $this->getIdKey();
	}
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return $this;
	}
}