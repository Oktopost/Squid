<?php
namespace Squid\MySql\Impl\Connectors\Object\Generic;


use Squid\MySql\Connectors\Object\Generic\IGenericIdentityConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;

use Squid\MySql\Impl\Connectors\Object\Identity\TPrimaryKeys;
use Squid\MySql\Impl\Connectors\Object\Identity\TIdentityDecorator;


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