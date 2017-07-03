<?php
namespace Squid\MySql\Impl\Connectors\Object\Identity;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;

use Squid\MySql\Impl\Connectors\Object\IdentityConnector;


class DecoratedIdentityConnector extends IdentityConnector
{
	private $genericObjectConnector;
	
	
	public function __construct()
	{
		parent::__construct();
	}


	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return $this->genericObjectConnector;
	}
	
	
	public function setGenericObjectConnector(IGenericObjectConnector $connector)
	{
		$this->genericObjectConnector = $connector;
	}
}