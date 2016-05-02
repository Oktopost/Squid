<?php
namespace Squid\MySql;


use Squid\MySql\Config\ConfigLoadersCollection;
use Squid\MySql\Config\ConfigCollection;
use Squid\MySql\Config\IConfigLoader;
use Squid\MySql\Config\MySqlConnectionConfig;
use Squid\MySql\Config\ConfigParser;


class ConfigFacade
{
	/** @var ConfigCollection */
	private $collection;
	
	/** @var ConfigLoadersCollection */
	private $connectionLoader;
	
	
	public function __construct()
	{
		$this->connectionLoader = new ConfigLoadersCollection();
		$this->collection = new ConfigCollection($this->connectionLoader);
	}


	/**
	 * @param string $name
	 * @param array $config
	 * @return static
	 */
	public function addConfig($name, array $config) 
	{
		$this->addConfigObject($name, ConfigParser::parse($config)); 
		return $this;
	}

	/**
	 * @param string $name
	 * @param MySqlConnectionConfig $config
	 * @return static
	 */
	public function addConfigObject($name, MySqlConnectionConfig $config) 
	{
		$this->collection->add($name, $config);
		return $this;
	}

	/**
	 * @param IConfigLoader $loader
	 * @return static
	 */
	public function addLoader(IConfigLoader $loader) 
	{
		$this->connectionLoader->add($loader);
		return $this;
	}
}