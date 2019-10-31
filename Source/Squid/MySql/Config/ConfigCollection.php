<?php
namespace Squid\MySql\Config;


use Squid\MySql\Impl\Connection\MySqlConnection;
use Squid\Exceptions\SquidException;


class ConfigCollection
{
	/** @var MySqlConnectionConfig[] */
	private $configs = [];

	/** @var IConfigLoader*/
	private $configLoader;
	
	
	/**
	 * @param IConfigLoader $loader
	 */
	public function __construct(IConfigLoader $loader) 
	{
		$this->configLoader = $loader;
	}
	
	
	/**
	 * @return MySqlConnectionConfig[]
	 */
	public function getAllConnections(): array
	{
		return array_values($this->configs);
	}
	
	public function add(string $name, MySqlConnectionConfig $connection) 
	{
		if (isset($this->configs[$name]))
			throw new SquidException("Config $name already exists");
		
		$this->configs[$name] = $connection;
	}
	
	/**.
	 * @param string $name
	 * @return MySqlConnectionConfig
	 */
	public function get($name): MySqlConnectionConfig
	{
		if (!isset($this->configs[$name]))
			$this->configs[$name] = $this->configLoader->getConfig($name);
		
		return $this->configs[$name];
	}
	
	public function has(string $name): bool  
	{
		return isset($this->configs[$name]) || $this->configLoader->hasConfig($name);
	}
}