<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\Polymorphic\IPolymorphicIdConnector;

use Squid\MySql\Impl\Connectors\Object\Primary\TIdComposition;


class PolymorphicIdConnector extends PolymorphicConnector implements IPolymorphicIdConnector
{
	use TIdComposition;
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return $this;
	}
}