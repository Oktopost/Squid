<?php
namespace Squid\MySql\Impl\Connection\Executors\RetryOnError\Validators;


use Squid\MySql\Exceptions\MySqlException;
use Squid\MySql\Impl\Connection\Executors\RetryOnError\Base\AbstractErrorValidator;

use Structura\Strings;


class DeadlockFound extends AbstractErrorValidator
{
	private const MYSQL_DEAD_LOCK_CODE = 1213;
	private const PDO_DEAD_LOCK_CODE = 40001;
	
	
	private function stringContainsCheck(\Throwable $t): bool
	{
		return Strings::contains($t->getMessage(), "Deadlock found when trying to get lock; try restarting transaction");
	}
	
	/**
	 * For PHP 7.4
	 * @param \Throwable $t
	 * @return bool
	 */
	private function mySQLErrorCheck(\Throwable $t): bool
	{
		return 
			$t instanceof MySqlException &&
			$t->getCode() == self::MYSQL_DEAD_LOCK_CODE;
	}
	
	/**
	 * For PHP 8.1
	 * @param \Throwable $e
	 * @return bool
	 */
	private function pdoErrorCheck(\Throwable $t): bool
	{
		return 
			$t instanceof \PDOException && 
			$t->getCode() == self::PDO_DEAD_LOCK_CODE;
	}
	
	
	/**
	 * @param int $ms Number of ms to wait before trying again
	 * @param int $retries Maximum number of retries
	 */
	public function __construct($ms = 200, $retries = 10)
	{
		parent::__construct($ms, $retries);
	}
	
	
	/**
	 * @param \Exception $e
	 * @return bool
	 */
	public function isHandled(\Exception $e)
	{
		return
			$this->mySQLErrorCheck($e) || 
			$this->pdoErrorCheck($e) || 
			$this->stringContainsCheck($e);
	}
}