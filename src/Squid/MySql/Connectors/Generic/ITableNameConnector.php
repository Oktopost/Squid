<?php
namespace Squid\MySql\Connectors\Generic;


interface ITableNameConnector
{
	/**
	 * @param string $table
	 * @return static
	 */
	public function setTable($table);
	
	/**
	 * @return string
	 */
	public function getTable();
}