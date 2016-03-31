<?php
namespace Squid\Base\Cmd;


use \Squid\Base\ICmdCreator;


/**
 * Other none Dml/Query operations.
 */
interface ICmdController extends ICmdCreator 
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
	 * @return bool True on success; otherwise false.
	 */
	public function startTransaction();
	
	/**
	 * Commit current transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool True on success; otherwise false.
	 */
	public function commit();
	
	/**
	 * Rollback current transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool True on success; otherwise false.
	 */
	public function rollback();
	
	/**
	 * Is there a pending transaction right now, waiting to be commited.
	 * @return bool Is there a transaction pending.
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
}