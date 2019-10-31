<?php
namespace Squid\MySql\Command;


interface ICmdDB extends IMySqlCommandConstructor 
{	
	/**
	 * @return array Array of all tables in DB.
	 */
	public function listTables();
	
	/**
	 * @see http://dev.mysql.com/doc/refman/5.7/en/drop-table.html
	 * @see http://dev.mysql.com/doc/refman/5.7/en/server-system-variables.html#sysvar_foreign_key_checks
	 * @param string $table
	 * @param bool $withForeignKeyCheck If false, set foreign_key_checks to 0.
	 * @return bool
	 */
	public function dropTable($table, $withForeignKeyCheck = true);
	
	/**
	 * @param string $table
	 * @return string|bool
	 */
	public function showCreateTable($table);
	
	/**
	 * @return string
	 */
	public function getDatabaseName();
}