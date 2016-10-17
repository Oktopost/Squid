<?php
namespace Squid\MySql\Impl\Connection\Executors\RetryOnError\Validators;


use Squid\MySql\Impl\Connection\Executors\RetryOnError\Base\AbstractErrorValidator;


class DeadlockFound extends AbstractErrorValidator
{
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
		return $this->isMessageMatch(
			$e, 
			"/^Mysqli statement execute error : Deadlock found when trying to get lock; try restarting transaction$/"); 
	}
}