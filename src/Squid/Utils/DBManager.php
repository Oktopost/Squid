<?php
namespace Squid\Utils;


use Squid\DBConn;


class DBManager {
	
	/** @var DBConn */
	private $connection;
	
	
	/**
	 * @param DBConn $conn
	 */
	public function __construct(DBConn $conn) {
		$this->connection = $conn;
	}
	
	
	/**
	 * @return bool
	 */
	public function dropAllTables() {
		$tables = $this->connection->db()->listTables();
		
		if (!$tables) {
			
			// If empty array nothing to drop, otherwise error.
			return is_array($tables);
		}
		
		return $this->connection->db()->dropTable($tables, false);
	}
	
	/**
	 * @return array Array of create table commands in order they should run, taking into account the foreign keys.
	 */
	public function getCreateTables() {
		$dbCmd = $this->connection->db();
		$result = [];
		
		foreach ($this->getAllTablesSortedByConstraints() as $table) {
			$result[] = $dbCmd->showCreateTable($table);
		}
		
		return $result;
	}
	
	
	/**
	 * @todo <alexey> 2016-02-15 This operation should be done by a separate class.
	 * @return array
	 */
	private function getAllTablesSortedByConstraints() {
		$dbCmd = $this->connection->db();
		
		$dbName = $dbCmd->getDatabaseName();
		$allTables = $dbCmd->listTables();
		
		$allTableConstraints = array_combine(
			array_values($allTables), 
			array_fill(0, count($allTables), [])
		);
		
		foreach ($this->getConstraints($dbName) as $row) {
			$allTableConstraints[$row[0]][] = $row[1];
		}
		
		return $this->sortTableByConstraints($allTableConstraints);
	}
	
	/**
	 * @param string $dbName
	 * @return array
	 */
	private function getConstraints($dbName) {
		return $this
			->connection->select()
			->columns(['TABLE_NAME', 'REFERENCED_TABLE_NAME'])
			->from('INFORMATION_SCHEMA.KEY_COLUMN_USAGE')
			->byField('CONSTRAINT_SCHEMA', $dbName)
			->where('REFERENCED_COLUMN_NAME IS NOT NULL')
			->where('REFERENCED_TABLE_NAME != TABLE_NAME')
			->queryAll(false);
	}
	
	/**
	 * @param array $constraintsMap This map must contain all tables, event thous that don't have constraints.
	 * @return array
	 */
	private function sortTableByConstraints(array $constraintsMap) {
		$sortedTables = [];
		
		while (count($constraintsMap) > 0) {
			$tableNames = array_keys($constraintsMap);
			
			foreach ($tableNames as $tableName) {
				if (count($constraintsMap[$tableName]) == 0) {
					$sortedTables[] = $tableName;
					unset($constraintsMap[$tableName]);
					continue;
				}
				
				$constraintsMap[$tableName] = array_values(
					array_diff(
						$constraintsMap[$tableName],
						$sortedTables
					));
			}
		}
		
		return $sortedTables;
	}
}