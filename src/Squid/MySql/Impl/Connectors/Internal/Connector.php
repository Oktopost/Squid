<?php
namespace Squid\MySql\Impl\Connectors\Internal;


use Squid\MySql\IMySqlConnector;
use Squid\MySql\Connectors\IConnector;


abstract class Connector implements IConnector
{
	/** @var IMySqlConnector */
	private $connector;
	
	
	public function __construct(Connector $connector = null)
	{
		if ($connector)
		{
			$this->connector = $connector->getConnector();
		}
	}


	/**
	 * @return IMySqlConnector
	 */
	public function getConnector(): IMySqlConnector
	{
		return $this->connector;
	}
	
	/**
	 * @param IMySqlConnector $connector
	 * @return IConnector|static
	 */
	public function setConnector(IMySqlConnector $connector): IConnector
	{
		$this->connector = $connector;
		return $this;
	}
}