<?php
namespace Squid\MySql\Impl\Connection\Executors\RetryOnError\Validators;


use Squid\MySql\Exceptions\MySqlException;
use Squid\MySql\Impl\Connection\Executors\RetryOnError\Base\AbstractErrorValidator;


class DeadlockFound extends AbstractErrorValidator
{
	private const DEAD_LOCK_CODE = 1213;
	
	
	/**
	 * @param int $ms Number of ms to wait before trying again
	 * @param int $retries Maximum number of retries
	 */
	public function __construct($ms = 20, $retries = 3)
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
			$e instanceof MySqlException &&
			$e->getCode() == self::DEAD_LOCK_CODE;
	}
}