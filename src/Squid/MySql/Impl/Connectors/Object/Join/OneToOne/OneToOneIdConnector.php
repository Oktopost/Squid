<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\OneToOne;


use Squid\MySql\Connectors\Object\Join\OneToOne\IOneToOneIdConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;

use Squid\MySql\Impl\Connectors\Object\Primary\TIdComposition;


class OneToOneIdConnector extends OneToOneConnector implements IOneToOneIdConnector
{
	use TIdComposition;
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return $this;
	}

	protected function getIdKey(): array
	{
		// TODO: Implement getIdKey() method.
	}

	protected function getPrimaryKeys(): array
	{
		// TODO: Implement getPrimaryKeys() method.
	}
}