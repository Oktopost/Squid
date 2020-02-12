<?php
namespace Squid\MySql\Impl\Connectors\Objects\Generic;


use Squid\MySql\Connectors\Objects\Generic\IGenericIdentityConnector;
use Squid\MySql\Connectors\Objects\Generic\IGenericObjectConnector;

use Squid\MySql\Impl\Connectors\Objects\Identity\TPrimaryKeys;
use Squid\MySql\Impl\Connectors\Objects\Identity\TIdentityDecorator;


class GenericIdentityConnector extends GenericObjectConnector implements 
	IGenericIdentityConnector
{
	use TPrimaryKeys;
	use TIdentityDecorator;
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return $this;
	}
}