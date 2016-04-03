<?php
namespace Squid\Base\Cmd;


use \Squid\Base\IMySqlCommand;


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