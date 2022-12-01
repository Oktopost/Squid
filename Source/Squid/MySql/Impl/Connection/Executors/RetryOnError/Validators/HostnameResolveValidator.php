<?php
namespace Squid\MySql\Impl\Connection\Executors\RetryOnError\Validators;


use Squid\MySql\Impl\Connection\Executors\RetryOnError\Base\AbstractErrorValidator;


class HostnameResolveValidator extends AbstractErrorValidator
{
	/**
	 * @param int $ms Number of ms to wait before trying again
	 * @param int $retries Maximum number of retries
	 */
	public function __construct($ms = 2, $retries = 2)
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
			$this->isMessageMatch($e, "/^.*Temporary failure in name resolution.*$/") ||
			$this->isMessageMatch($e, "/^.*Name or service not known.*$/"); 
	}
}