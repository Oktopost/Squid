<?php
namespace Squid\MySql;


use Squid\MySql\Connection\MySqlConnectionConfig;


interface IConnectionLoader 
{
	/**
	 * @param string $connName
	 * @return array|MySqlConnectionConfig
	 */
	public function getConnectionConfig($connName);
	
	/**
	 * @param string $connName
	 * @return bool
	 */
	public function hasConnectionConfig($connName);
}