<?php
namespace Squid\Base\CmdCreators;


use \Squid\Base\ICmdCreator;


/**
 * Controller on a DB level.
 */
interface ICmdDB extends ICmdCreator {
	
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