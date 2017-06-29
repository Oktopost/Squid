<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\OneToOne;


use Squid\MySql\Connectors\Object\Join\OneToOne\IOneToOneIdentityConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;

use Squid\MySql\Impl\Connectors\Object\Identity\TIdentityComposition;


class OneToOneIdentityConnector extends OneToOneConnector implements IOneToOneIdentityConnector
{
	use TIdentityComposition;
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return $this;
	}
}