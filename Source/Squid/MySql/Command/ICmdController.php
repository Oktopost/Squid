<?php
namespace Squid\MySql\Command;


interface ICmdController extends IMySqlCommandConstructor
{
	/**
	 * Get the last inserted ID.
	 * @see http://dev.mysql.com/doc/refman/5.7/en/information-functions.html#function_last-insert-id
	 * @return mixed Last inserted auto increment ID.
	 */
	public function lastId();
	
	/**
	 * Start a new transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool
	 */
	public function startTransaction();
	
	/**
	 * Commit current transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool
	 */
	public function commit();
	
	/**
	 * Rollback current transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool
	 */
	public function rollback();
	
	/**
	 * @return bool
	 */
	public function isInTransaction();
	
	/**
	 * @param string $tableName
	 * @see http://dev.mysql.com/doc/refman/5.7/en/describe.html
	 * @return array|bool
	 */
	public function describe($tableName);
	
	/**
	 * @param array $tables Assoc array of tables, were key is old name and value is new name
	 * @see http://dev.mysql.com/doc/refman/5.7/en/rename-table.html
	 * @return bool
	 */
	public function rename($tables);

	/**
	 * Rename tableA to tableB and tableB to tableA.
	 * @param string $tableA Name of the first table.
	 * @param string $tableB Name of the second table.
	 * @return mixed
	 */
	public function rotate($tableA, $tableB);
}