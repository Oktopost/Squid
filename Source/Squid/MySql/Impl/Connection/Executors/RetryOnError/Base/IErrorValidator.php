<?php
namespace Squid\MySql\Impl\Connection\Executors\RetryOnError\Base;


interface IErrorValidator
{
	/**
	 * @param \Exception $e
	 * @return bool
	 */
	public function isHandled(\Exception $e);

	/**
	 * @return array ["ms-delay" => Number of ms to wait before trying again, "retries" => Maximum number of retries] 
	 */
	public function config();
}