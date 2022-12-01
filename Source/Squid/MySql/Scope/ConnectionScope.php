<?php
namespace Squid\MySql\Scope;


use Squid\MySql\Config\MySqlConnectionConfig;
use Squid\MySql\Connection\IMySqlConnection;


class ConnectionScope
{
	private MySqlConnectionConfig $currentConfig;
	private IMySqlConnection $connection;
	
	
	public function __construct(MySqlConnectionConfig $config, IMySqlConnection $connection)
	{
		$this->currentConfig = $config;
		$this->connection = $connection;
	}
	
	
	/**
	 * @param string|MySqlConnectionConfig $db
	 * @return bool
	 */
	public function is($db): bool
	{
		if (!is_string($db)) 
			$db = $db->DB;
		
		return $this->currentConfig->DB === $db;
	}
	
	public function set(MySqlConnectionConfig $config)
	{
		if (!$config->DB || $this->currentConfig->DB === $config->DB )
			return;
		
		$this->currentConfig = $config;
		$this->connection->setConfig($config);
	}
	
	public function connection(): IMySqlConnection
	{
		return $this->connection;
	}
	
	public function isSame(MySqlConnectionConfig $config): bool
	{
		return 
			$this->currentConfig->User == $config->User && 
			$this->currentConfig->Host == $config->Host &&
			$this->currentConfig->Port == $config->Port;
	}
	
	public function hash()
	{
		return self::hashConfig($this->currentConfig);
	}
	
	public function createDecorator(MySqlConnectionConfig $config): ConnectorScopeDecorator
	{
		return new ConnectorScopeDecorator($config, $this);
	}
	
	public static function hashConfig(MySqlConnectionConfig $config)
	{
		return sha1($config->Host . $config->Port . $config->User);
	}
}