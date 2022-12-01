<?php
namespace Squid;


use Squid\MySql\ConfigFacade;
use Squid\MySql\IMySqlConnector;

use Squid\MySql\Impl\MySqlConnector;
use Squid\MySql\Impl\Connection\ConnectionBuilder;

use Squid\Utils\EmptyWhereInHandler;


class MySql
{
	private ConfigFacade $configFacade;
	
	/** @var MySqlConnector[] */
	private array $sharedConnectors = [];
	
	
	private function getConnectionBuilder(): ConnectionBuilder
	{
		$connectionBuilder = new ConnectionBuilder();
		$connectionBuilder->setDecorators($this->configFacade->getDecorators());
		
		return $connectionBuilder;
	}
	
	
	public function __construct()
	{
		$this->configFacade = new ConfigFacade();
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
			$config = $this->configFacade->getConfig($name);
			$connection = $this->getConnectionBuilder()->create($config);
			$this->sharedConnectors[$name] = new MySqlConnector($name, $connection);
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
		$config = $this->configFacade->getConfig($name);
		
		$config = clone $config;
		$config->ReuseConnection = false;
		
		$connection = $this->getConnectionBuilder()->create($config);
		
		return new MySqlConnector($name, $connection);
	}
	
	public function closeAll()
	{
		foreach ($this->sharedConnectors as $connector)
		{
			$connector->close();
		}
	}
	
	public static function staticConnector(array $config)
	{
		$mysql = new MySql();
		$mysql->config()->addConfig('main', $config);
		return $mysql->getConnector('main');
	}
	
	public static function setEmptyWhereInHandler(callable $callback): void
	{
		EmptyWhereInHandler::set($callback);
	}
}