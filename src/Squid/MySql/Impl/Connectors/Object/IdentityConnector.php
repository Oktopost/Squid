<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Impl\Connectors\Object\Generic\GenericObjectConnector;
use Squid\MySql\Impl\Connectors\Object\Identity\AbstractIdentityConnector;


class IdentityConnector extends AbstractIdentityConnector
{
	/** @var IGenericObjectConnector */
	private $genericConnector;
	

	protected function getGenericConnector(): IGenericObjectConnector
	{
		if (!$this->genericConnector)
			$this->genericConnector = new GenericObjectConnector($this);
		
		return $this->genericConnector;
	}
}