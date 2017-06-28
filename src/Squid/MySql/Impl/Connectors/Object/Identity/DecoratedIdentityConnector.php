<?php
namespace Squid\MySql\Impl\Connectors\Object\Identity;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;


class DecoratedIdentityConnector extends AbstractIdentityConnector
{
	private $genericObjectConnector;
	

	protected function getGenericConnector(): IGenericObjectConnector
	{
		return $this->genericObjectConnector;
	}
	
	
	public function setGenericObjectConnector(IGenericObjectConnector $connector)
	{
		$this->genericObjectConnector = $connector;
	}
}