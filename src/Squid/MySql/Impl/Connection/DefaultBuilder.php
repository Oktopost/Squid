<?php
namespace Squid\MySql\Impl\Connection;


use Squid\MySql\Config\MySqlConnectionConfig;
use Squid\MySql\Connection\IMySqlConnection;
use Squid\MySql\Connection\IConnectionBuilder;


class DefaultBuilder implements IConnectionBuilder
{
	/**
	 * @param MySqlConnectionConfig $config
	 * @return IMySqlConnection
	 */
	public function create(MySqlConnectionConfig $config)
	{
		$connection = new MySqlConnection();
		$connection->setConfig($config);
		return $connection;
	}
}