<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\Polymorphic\IPolymorphicIdentityConnector;

use Squid\MySql\Impl\Connectors\Object\Identity\TPrimaryKeys;
use Squid\MySql\Impl\Connectors\Object\Identity\TIdentityDecorator;


class PolymorphicIdentityConnector extends PolymorphicConnector implements IPolymorphicIdentityConnector
{
	use TPrimaryKeys;
	use TIdentityDecorator;
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector 
	{
		return $this;
	}
}