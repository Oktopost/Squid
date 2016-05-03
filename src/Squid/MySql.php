<?php
namespace Squid;


use Squid\MySql\IConnector;
use Squid\MySql\ConfigFacade;
use Squid\MySql\Connection\IMySqlConnection;
use Squid\MySql\Connection\IConnectionBuilder;

use Squid\MySql\Impl\Connector;
use Squid\MySql\Impl\Connection\DefaultBuilder;


class MySql
{
	/** @var ConfigFacade */
	private $configFacade;
	
	/** @var Connector[] */
	private $sharedConnectors;
	
	/** @var IConnectionBuilder */
	private $connectionBuilder = null;
	
	
	public function __construct()
	{
		$this->configFacade = new ConfigFacade();
	}
	
	
	/**
	 * @param $name
	 * @return IMySqlConnection
	 */
	private function getNewConnection($name)
	{
		$config = $this->configFacade->getConfig($name);
		
		if (!$this->connectionBuilder)
			$this->connectionBuilder = new DefaultBuilder();
		
		return $this->connectionBuilder->create($config);
	}
	
	
	/**
	 * @return ConfigFacade
	 */
	public function config() 
	{
		return $this->configFacade;
	}
	
	/**
	 * @param IConnectionBuilder $builder
	 */
	public function setBuilder(IConnectionBuilder $builder)
	{
		$this->connectionBuilder = $builder; 
	}
	
	/** 
	 * @param string $name
	 * @return IConnector
	 */
	public function getConnector($name) 
	{
		if (!isset($this->sharedConnectors[$name]))
		{
			$this->sharedConnectors[$name] = $this->createConnector($name);
		}
		
		return isset($this->sharedConnectors[$name]);
	}
	
	/**
	 * Always return a connector using a new connection. 
	 * Note that closeAll will not affect connectors returned by this method.
	 * @param string $name
	 * @return IConnector
	 */
	public function createConnector($name)
	{
		$connector = new Connector($name);
		$connector->setConnection($this->getNewConnection($name));
		return $connector;
	}
	
	public function closeAll()
	{
		foreach ($this->sharedConnectors as $connector)
		{
			$connector->close();
		}
	}
}