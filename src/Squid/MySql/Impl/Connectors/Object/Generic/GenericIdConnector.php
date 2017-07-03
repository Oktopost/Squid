<?php
namespace Squid\MySql\Impl\Connectors\Object\Generic;


use Squid\MySql\Connectors\Object\Generic\IGenericIdConnector;

use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Impl\Connectors\Object\Primary\TIdKey;
use Squid\MySql\Impl\Connectors\Object\Primary\TIdDecorator;


class GenericIdConnector extends GenericObjectConnector implements IGenericIdConnector
{
	use TIdDecorator;
	use TIdKey;
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return $this;
	}
}