<?php
namespace Squid\MySql\Config;


interface IConfigLoader 
{
	/**
	 * @param string $connName
	 * @return MySqlConnectionConfig
	 */
	public function getConfig($connName);
	
	/**
	 * @param string $connName
	 * @return bool
	 */
	public function hasConfig($connName);
}