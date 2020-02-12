<?php
namespace Squid\MySql\Impl\Connectors\Objects\Primary;


use Squid\MySql\Connectors\Objects\Generic\IGenericObjectConnector;
use Squid\MySql\Impl\Connectors\Objects\IdConnector;


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