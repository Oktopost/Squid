<?php
namespace Squid\MySql\Impl\Connectors\Object\Generic;


use Squid\MySql\Connectors\Object\Generic\IGenericIdConnector;

use Squid\MySql\Impl\Connectors\Object\Primary\TIdComposition;
use Squid\MySql\Impl\Connectors\Object\Identity\TIdentityDecorator;


class GenericIdConnector extends GenericObjectConnector implements IGenericIdConnector
{
	use TIdentityDecorator;
	use TIdComposition;
	
	
	protected function getPrimaryKeys(): array
	{
		return $this->getIdKey();
	}
}