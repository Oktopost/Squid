<?php
namespace Squid\MySql\Cmd;


use \Squid\Common;
use \Squid\Base\Cmd\ICmdDB;


class CmdDB extends AbstractCommand implements ICmdDB {
	use Squid\MySql\Traits\CmdTraits\TDml;
	use Squid\MySql\Traits\CmdTraits\TQuery;
	
	
	private $command	= '';
	private $bind		= [];
	
	
	/**
	 * @return string
	 */
	public function assemble() {
		return $this->command;
	}
	
	/**
	 * @return array 
	 */
	public function bindParams() {
		return $this->bind;
	}
	
	
	/**
	 * @see http://dev.mysql.com/doc/refman/5.7/en/show-tables.html
	 * @return array Array of all tables in DB.
	 */
	public function listTables() {
		$this->setCommand('SHOW TABLES');
		return $this->queryColumn(true);
	}
	
	/**
	 * @see http://dev.mysql.com/doc/refman/5.7/en/drop-tablespace.html
	 * @param string $table
	 * @param bool $withForeignKeyCheck If false, set foreign_key_checks to 0.
	 * @return bool
	 */
	public function dropTable($table, $withForeignKeyCheck = true) {
		$table = '`' . implode('`, `', Common::toArray($table)) . '`';
		
		if (!$withForeignKeyCheck && !$this->setForeignKeyCheck(false)) {
			return false;
		}
		
		$result = $this->executeDmlCommand("DROP TABLE $table");
		
		if (!$withForeignKeyCheck) {
			$this->setForeignKeyCheck(true);
		}
		
		return $result;
	}
	
	/**
	 * @see http://dev.mysql.com/doc/refman/5.7/en/show-create-table.html
	 * @param string $table
	 * @return string|bool
	 */
	public function showCreateTable($table) {
		$this->setCommand("SHOW CREATE TABLE `$table`");
		
		$data = $this->queryRow(true);
		
		return ($data ? $data['Create Table'] : false);
	}
	
	/**
	 * @see http://dev.mysql.com/doc/refman/5.7/en/information-functions.html#function_database
	 * @return string Name of the current database.
	 */
	public function getDatabaseName() {
		$this->setCommand('SELECT DATABASE()');
		return $this->queryScalar();
	}
	
	
	/**
	 * @see http://dev.mysql.com/doc/refman/5.7/en/server-system-variables.html#sysvar_foreign_key_checks
	 * @param bool $value
	 * @return bool
	 */
	private function setForeignKeyCheck($value) {
		$value = ($value ? '1' : '0');
		
		return $this->executeDmlCommand("SET foreign_key_checks = $value");
	}
	
	/**
	 * @param string $cmd
	 * @param array $bind
	 * @return bool 
	 */
	private function executeDmlCommand($cmd, array $bind = array()) {
		$this->setCommand($cmd, $bind);
		return $this->executeDml();
	}
	
	/**
	 * @param string $cmd
	 * @param array $bind
	 */
	private function setCommand($cmd, array $bind = array()) {
		$this->command = $cmd;
		$this->bind = $bind;
	}
}