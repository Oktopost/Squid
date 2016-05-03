<?php
namespace Squid\MySql\Impl;


use Squid\MySql\Impl\Connection\MySqlConnection;
use Squid\MySql\Config\ConfigParser;

use Squid\MySql\IConnections;
use Squid\MySql\Config\IConfigLoader;
use Squid\MySql\Connection\IMySqlConnection;
use Squid\MySql\Connection\IConnectionBuilder;
use Squid\MySql\Config\MySqlConnectionConfig;

use Squid\Exceptions\SquidException;


class Connections implements IConnections {
	
	/** @var MySqlConnectionConfig[] */
	private $configs = [];
	
	/** @var IConfigLoader[] */
	private $loaders = [];
	
	/** @var IMySqlConnection[] */
	private $connections = [];
	
	/** @var IConnectionBuilder */
	private $builder = null;
	
	
	/**
	 * @param string $name
	 * @throws SquidException If connection for requested name not found.
	 * @return MySqlConnectionConfig
	 */
	private function findConnectionConfig($name)
	{
		if (isset($this->configs[$name]))
			return $this->configs[$name];
		
		foreach ($this->loaders as $loader)
		{
			if ($loader->hasConfig($name)) 
			{
				$this->addConfig($name, $loader->getConfig($name));
				return $this->configs[$name];
			}
		}
		
		throw new SquidException("Configuration for connection name '$name' not found");
	}
	
	/**
	 * @param MySqlConnectionConfig $config
	 * @return IMySqlConnection
	 */
	private function createConnection(MySqlConnectionConfig $config)
	{
		if (!$this->builder) 
			return new MySqlConnection($config);
		
		return $this->builder->create($config);
	}
	
	
	/**
	 * @param IConnectionBuilder $builder
	 * @return mixed
	 */
	public function setBuilder(IConnectionBuilder $builder)
	{
		$this->builder = $builder;
	}
	
	/**
	 * @param string $name Connection name
	 * @param MySqlConnectionConfig|array $config
	 * @return static
	 * @throws SquidException
	 */
	public function addConfig($name, $config)
	{
		if (!($config instanceof MySqlConnectionConfig)) 
		{
			if (!is_array($config)) 
				throw new SquidException('Configuration string must be array or instance of MySqlConnectionConfig');
			
			$config = ConfigParser::parse($config);
		}
		
		$this->configs[$name] = $config;
		
		return $this;
	}
	
	/**
	 * @param IConfigLoader $loader
	 * @return static
	 */
	public function addLoader(IConfigLoader $loader)
	{
		$this->loaders[] = $loader;
	}
	
	/**
	 * Get a connection by name. If connection for this name already exists, it's returned.
	 * @param string $name 
	 * @return IMySqlConnection
	 */
	public function get($name)
	{
		if (isset($this->connections[$name]))
			return $this->connections[$name];
		
		$config = $this->findConnectionConfig($name);
		$this->connections[$name] = $this->createConnection($config);
		
		return $this->connections[$name];
	}
	
	/**
	 * Always return a new connection.
	 * @param string $name
	 * @return IMySqlConnection
	 * @throws SquidException
	 */
	public function getNew($name)
	{
		$config = $this->findConnectionConfig($name);
		$connection = $this->createConnection($config);
		
		return $connection;
	}
	
	/**
	 * Close all open connections.
	 */
	public function closeAll()
	{
		foreach ($this->connections as $connection) 
		{
			$connection->close();
		}
	}
}