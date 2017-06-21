<?php
namespace Squid\MySql\Impl\Connectors;


use Squid\MySql\Connectors\IConnector;
use Squid\MySql\IMySqlConnector;


/**
 * @mixin IConnector
 */
class Connector implements IConnector
{
	/** @var IMySqlConnector */
	private $connector;
	
	
	public function __construct(IConnector $connector = null)
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
		if ($connector instanceof IConnector)
		{
			$this->setConnector($connector->getConnector());
		}
		else
		{
			$this->connector = $connector;
		}
		
		return $this;
	}
}