<?php
namespace Squid\MySql\Connectors;


use Squid\MySql\IMySqlConnector;
use Squid\Object\IObjectConnector;


interface IMySqlObjectConnector extends IObjectConnector
{
	/**
	 * @param IMySqlConnector $connector
	 * @return static
	 */
	public function setConnector(IMySqlConnector $connector);

	/**
	 * If not called, class name used.
	 * @param string $tableName
	 * @return static
	 */
	public function setTable($tableName);
}