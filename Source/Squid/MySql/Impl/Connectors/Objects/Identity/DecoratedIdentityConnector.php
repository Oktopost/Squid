<?php
namespace Squid\MySql\Impl\Connectors\Objects\Identity;


use Squid\MySql\Connectors\Objects\Generic\IGenericObjectConnector;

use Squid\MySql\Impl\Connectors\Objects\IdentityConnector;


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