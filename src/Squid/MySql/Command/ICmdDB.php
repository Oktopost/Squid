<?php
namespace Squid\MySql\Command;


use Squid\MySql\IMySqlCommand;


/**
 * Controller on a DB level.
 */
interface ICmdDB extends IMySqlCommand 
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
	 * @return string Name of the current database.
	 */
	public function getDatabaseName();
}