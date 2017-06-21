<?php
namespace Squid\MySql\Impl\Connectors;


use Squid\MySql\Connectors\IGenericConnector;
use Squid\MySql\IMySqlConnector;


/**
 * @mixin IGenericConnector
 */
trait TGenericConnector
{
	/** @var IMySqlConnector */
	private $_connector;
	
	
	public function getConnector(): IMySqlConnector
	{
		return $this->_connector;
	}
	
	
	/**
	 * @param IMySqlConnector $connector
	 * @return IGenericConnector|static
	 */
	public function setConnector(IMySqlConnector $connector): IGenericConnector
	{
		$this->_connector = $connector;
		return $this;
	}
}