<?php
namespace Squid\MySql\Cmd;


use \Squid\Base\Cmd\ICmdLock;


class CmdLock extends AbstractCommand implements ICmdLock {
	
	/**
	 * @var string Command to execute. 
	 */
	private $sql;
	
	/**
	 * @var array Array of bind params.
	 */
	private $params;
	
	
	/**
	 * Get the bind parameters.
	 * @return array Array of bind params.
	 */
	public function bindParams() {
		return $this->params;
	}
	
	/**
	 * Generate the query string.
	 * @return string Currently set query.
	 */
	public function assemble() {
		return $this->sql;
	}
	
	
	/**
	 * Lock the given keyword.
	 * @param string $key Key to use to lock.
	 * @param int $timeout Number of seconds to wait for the lock. Can't be greater than 5.
	 * @return true on successfull lock, false otherwise.
	 */
	public function lock($key, $timeout = 5) {
		if (!is_int($timeout) || $timeout < 0 || $timeout > 5) {
			throw new \Exception("Invalid value for timeout '$timeout'");
		}
		
		$this->sql = 'SELECT GET_LOCK(?, ?)';
		$this->params = array($key, $timeout);
		
		$result = parent::execute();
		
		if (!$result || $result->errorCode() != '00000') {
			return false;
		}
		
		$row = $result->fetch(\PDO::FETCH_NUM);
		return ((int)$row[0] == 1);
	}
	
	/**
	 * Unlock given lock.
	 * @param string $key Key to unlock.
	 */
	public function unlock($key) {
		$this->sql = 'DO RELEASE_LOCK(?)';
		$this->params = array($key);
		parent::execute();
	}
	
	/**
	 * @param callable $callback
	 * @param string $key
	 * @param int $timeout In seconds
	 * @return mixed|bool False if failed to acquire lock.
	 */
	public function safe($callback, $key, $timeout = 5) {
		if (!$this->lock($key, $timeout)) {
			return false;
		}
		
		try {
			return $callback();
		} finally {
			$this->unlock($key);
		}
	}
	
	/**
	 * Privent the use of execute for lock/unlock.
	 * @throws \Exception
	 */
	public function execute() {
		throw new \Exception('Use lock or unlock methods for this command!');
	}
}