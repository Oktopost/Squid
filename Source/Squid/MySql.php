<?php
namespace Squid;


use Squid\MySql\ConfigFacade;
use Squid\MySql\IMySqlConnector;
use Squid\MySql\Config\Property;
use Squid\MySql\Connection\IMySqlConnection;
use Squid\MySql\Connection\IMySqlExecuteDecorator;

use Squid\MySql\Impl\MySqlConnector;
use Squid\MySql\Impl\Connection\ConnectionBuilder;

use Squid\Utils\EmptyWhereInHandler;


class MySql extends Property
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
	 * @param string|array $name
	 * @param array $config
	 * @return static
	 */
	public function addConnector($name, array $config = []) 
	{
		$this->config()->addConfig($name, $config);
		return $this;
	}
	
	/**
	 * @param string[]|IMySqlExecuteDecorator[] ...$decorators
	 * @return static
	 */
	public function addDecorator(...$decorators)
	{
		foreach ($decorators as &$decorator)
		{
			if (is_string($decorator))
			{
				$decorator = new $decorator;
			}
		}
		
		$this->config()->addExecuteDecorator(...$decorators);
		return $this;
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