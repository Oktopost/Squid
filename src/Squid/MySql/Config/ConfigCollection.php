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
	 * Load connection by name.
	 * @param string $name
	 * @return MySqlConnection
	 */
	private function load($name)
	{
		$config = $this->configLoader->getConfig($name);
		
		$conn = new MySqlConnection();
		$conn->connect($config);
		
		return $conn;
	}
	
	
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
	public function getAllConnections()
	{
		return array_values($this->configs);
	}

	/**
	 * @param string $name
	 * @param MySqlConnectionConfig $connection
	 * @throws SquidException
	 */
	public function add($name, MySqlConnectionConfig $connection) 
	{
		if (isset($this->configs[$name]))
			throw new SquidException("Config $name already exists");
		
		$this->configs[$name] = $connection;
	}
	
	/**.
	 * @param string $name
	 * @return MySqlConnectionConfig
	 */
	public function get($name) 
	{
		if (!isset($this->configs[$name]))
			$this->configs[$name] = $this->load($name);
		
		return $this->configs[$name];
	}
	
	/**.
	 * @param string $name
	 * @return MySqlConnectionConfig
	 */
	public function has($name) 
	{
		return isset($this->configs[$name]) || $this->configLoader->hasConfig($name);
	}
}