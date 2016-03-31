<?php
namespace Squid\MySql\Cmd;


use \Squid\Base\Cmd\ICmdController;


class CmdController extends AbstractCommand implements ICmdController {
	use Squid\MySql\Traits\CmdTraits\TDml;
	use Squid\MySql\Traits\CmdTraits\TQuery;
	
	
	private $command;
	private $bind;
	
	/** @var bool */
	private $isInTransaction = false;
	
	
	public function assemble() {
		return $this->command;
	}
	
	public function bindParams() {
		return $this->bind;
	}
	
	/**
	 * Get the last inserted ID.
	 * @return mixed Last inserted auto increment ID.
	 */
	public function lastId() {
		$this->command	= 'SELECT LAST_INSERT_ID()';
		$this->bind		= array();
		
		return $this->queryInt();
	}
	
	/**
	 * Start a new transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool True on success; otherwise false.
	 */
	public function startTransaction() {
		// TODO: Start transaction
		$this->isInTransaction = true;
		
		return true;
	}
	
	/**
	 * Commit current transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool True on success; otherwise false.
	 */
	public function commit() {
		// TODO: End transaction
		$this->isInTransaction = false;
		
		return true;
	}
	
	/**
	 * Rollback current transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool True on success; otherwise false.
	 */
	public function rollback() {
		// TODO: Rollback
		$this->isInTransaction = false;
		
		return true;
	}
	
	/**
	 * Is there a pending transaction right now, waiting to be commited.
	 * @return bool Is there a transaction pending.
	 */
	public function isInTransaction() {
		return $this->isInTransaction;
	}
	
	/**
	 * @param string $tableName
	 * @return array|bool
	 */
	public function describe($tableName) {
		$this->command	= 'DESCRIBE ' . $tableName;
		$this->bind		= array();
		
		return $this->queryAll(true);
	}
	
	/**
	 * @param array $tables Assoc array of tables, were key is old name and value is new name
	 * @return bool
	 */
	public function rename($tables) {
		$this->command	= 'RENAME TABLE ';
		$this->bind		= array();
		
		$renames = array();
		
		foreach ($tables as $oldName => $newName) {
			$renames[] = "`$oldName` TO `$newName`";
		}
		
		$this->command .= implode(',', $renames);
		
		return $this->executeDml();
	}
}