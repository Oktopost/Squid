<?php
namespace Squid\MySql;


use Squid\MySql\Config\IConfigLoader;
use Squid\MySql\Connection\IMySqlConnection;
use Squid\MySql\Connection\IConnectionBuilder;
use Squid\MySql\Config\MySqlConnectionConfig;


interface IConnections 
{
	/**
	 * @param IConnectionBuilder $builder
	 * @return static
	 */
	public function setBuilder(IConnectionBuilder $builder);
	
	/**
	 * @param string $name Connection name
	 * @param MySqlConnectionConfig|array $config
	 * @return static
	 */
	public function addConfig($name, $config);
	
	/**
	 * @param IConfigLoader $loader
	 * @return static
	 */
	public function addLoader(IConfigLoader $loader);
	
	/**
	 * Get a connection by name. If connection for this name already exists, it's returned.
	 * @param string $name 
	 * @return IMySqlConnection
	 */
	public function get($name);
	
	/**
	 * Always return a new connection.
	 * @param string $name Name of the connection to get.
	 * @return IMySqlConnection
	 */
	public function getNew($name);
	
	/**
	 * Close all open connections.
	 */
	public function closeAll();
}