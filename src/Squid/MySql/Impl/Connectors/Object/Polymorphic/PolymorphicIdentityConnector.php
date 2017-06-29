<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\Polymorphic\IPolymorphicIdentityConnector;

use Squid\MySql\Impl\Connectors\Object\Identity\TIdentityComposition;


class PolymorphicIdentityConnector extends PolymorphicConnector implements IPolymorphicIdentityConnector
{
	use TIdentityComposition;
	
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector 
	{
		return $this;
	}
}