<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Impl\Connectors\Object\IdConnector;


class DecoratedIdConnector extends IdConnector
{
	private $genericObjectConnector;
	

	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return $this->genericObjectConnector;
	}
	
	
	public function setGenericObjectConnector(IGenericObjectConnector $connector)
	{
		$this->genericObjectConnector = $connector;
	}
}