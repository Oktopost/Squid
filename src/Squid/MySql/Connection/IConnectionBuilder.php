<?php
namespace Squid\MySql\Connection;


interface IConnectionBuilder 
{
	/**
	 * @param MySqlConnectionConfig $config
	 * @return IMySqlConnection
	 */
	public function create(MySqlConnectionConfig $config);
}