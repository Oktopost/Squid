<?php
namespace Squid\MySql;


use Squid\Exceptions\SquidException;
use Squid\MySql\Config\ConfigParser;
use Squid\MySql\Config\IConfigLoader;
use Squid\MySql\Config\ConfigCollection;
use Squid\MySql\Config\MySqlConnectionConfig;
use Squid\MySql\Config\ConfigLoadersCollection;
use Squid\MySql\Connection\IMySqlExecuteDecorator;


class ConfigFacade
{
	/** @var ConfigCollection */
	private $collection;
	
	/** @var ConfigLoadersCollection */
	private $connectionLoader;
	
	/** @var IMySqlExecuteDecorator[] */
	private $executeDecorators = [];
	
	
	public function __construct()
	{
		$this->connectionLoader = new ConfigLoadersCollection();
		$this->collection = new ConfigCollection($this->connectionLoader);
	}


	/**
	 * Equal to calling addConfig('main', $config)
	 * @param array $config
	 * @return static
	 */
	public function setConfig(array $config = []) 
	{ 
		return $this->addConfig('main', $config);
	}
	
	/**
	 * @param string|array $name 
	 * @param array $config
	 * @return static
	 */
	public function addConfig($name, array $config = []) 
	{
		if (is_array($name))
		{
			$config = $name;
			$name = 'main';
		}
			
		if (!is_string($name))
			throw new SquidException('Connection name must be a string!');
		
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

	/**
	 * @param IMySqlExecuteDecorator[] $decorators The last decorator passed to this most top decorator in
	 * the stuck, meaning - last decorator called first and the first decorator is the decorator to call original
	 * mysql connector's execute function directly.
	 * Calling addExecuteDecorator($a, $b) will result in $connector::$execute invoking $a::execute 
	 * calling $b::execute invoking $connection::$execute
	 */
	public function addExecuteDecorator(...$decorators)
	{
		$this->executeDecorators = array_merge($this->executeDecorators, $decorators);
	}
	
	/**
	 * @param string $name
	 * @return MySqlConnectionConfig
	 */
	public function getConfig($name)
	{
		return $this->collection->get($name);
	}

	/**
	 * @return Connection\IMySqlExecuteDecorator[]
	 */
	public function getDecorators()
	{
		return $this->executeDecorators;
	}
}