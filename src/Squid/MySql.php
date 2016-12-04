<?php
namespace Squid;


use Squid\MySql\ConfigFacade;
use Squid\MySql\IMySqlConnector;
use Squid\MySql\Connection\IMySqlConnection;

use Squid\MySql\Impl\MySqlConnector;
use Squid\MySql\Impl\Connection\ConnectionBuilder;


class MySql
{
	/** @var ConfigFacade */
	private $configFacade;
	
	/** @var MySqlConnector[] */
	private $sharedConnectors;
	
	/** @var ConnectionBuilder */
	private $connectionBuilder = null;
	
	
	public function __construct()
	{
		$this->configFacade = new ConfigFacade();
	}
	
	
	/**
	 * @param string $name
	 * @return IMySqlConnection
	 */
	private function getNewConnection($name)
	{
		$config = $this->configFacade->getConfig($name);
		
		if (!$this->connectionBuilder)
		{
			$this->connectionBuilder = new ConnectionBuilder();
			$this->connectionBuilder->setDecorators($this->configFacade->getDecorators());
		}
		
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
	 * @param string $name
	 * @return IMySqlConnector
	 */
	public function getConnector($name = 'main') 
	{
		if (!isset($this->sharedConnectors[$name]))
		{
			$this->sharedConnectors[$name] = $this->createConnector($name);
		}
		
		return $this->sharedConnectors[$name];
	}
	
	/**
	 * Always return a connector using a new connection. 
	 * Note that closeAll will not affect connectors returned by this method.
	 * @param string $name
	 * @return IMySqlConnector
	 */
	public function createConnector($name)
	{
		$connector = new MySqlConnector($name);
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