<?php
namespace Squid\MySql\Connection;


use Squid\MySql\Config\MySqlConnectionConfig;

interface IConnectionBuilder 
{
	/**
	 * @param MySqlConnectionConfig $config
	 * @return IMySqlConnection
	 */
	public function create(MySqlConnectionConfig $config);
}