<?php
namespace Squid\MySql\Command;


interface ICmdTransaction extends IMySqlCommand
{
	/**
	 * Start a new transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool
	 */
	public function startTransaction(): bool;
	
	/**
	 * Commit current transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool
	 */
	public function commit(): bool;
	
	/**
	 * Rollback current transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool
	 */
	public function rollback(): bool;

	/**
	 * Are we currently inside a transaction.
	 * @return bool
	 */
	public function isInTransaction(): bool;

	/**
	 * Execute given operation in a transaction. If false is returned or an exception is thrown, rollback
	 * will be executed. Otherwise, a commit is called.  
	 * @param IMySqlCommandConstructor|IMySqlCommandConstructor[]|callable $operation
	 * @return mixed
	 */
	public function executeInTransaction($operation);
}