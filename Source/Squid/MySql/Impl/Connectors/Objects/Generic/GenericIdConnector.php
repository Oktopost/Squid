<?php
namespace Squid\MySql\Impl\Connectors\Objects\Generic;


use Squid\MySql\Connectors\Objects\Generic\IGenericIdConnector;

use Squid\MySql\Connectors\Objects\Generic\IGenericObjectConnector;
use Squid\MySql\Impl\Connectors\Objects\Primary\TIdKey;
use Squid\MySql\Impl\Connectors\Objects\Primary\TIdDecorator;


class GenericIdConnector extends GenericObjectConnector implements IGenericIdConnector
{
	use TIdDecorator;
	use TIdKey;
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return $this;
	}
}