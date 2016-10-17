<?php
namespace Squid\MySql\Impl\Connection\Executors\RetryOnError\Base;


abstract class AbstractErrorValidator implements IErrorValidator
{
	/** @var array */
	private $config;


	/**
	 * @param \Exception $e
	 * @param string $regex
	 * @return bool
	 */
	protected function isMessageMatch(\Exception $e, $regex)
	{
		return (preg_match($regex, $e->getMessage()) === 1);
	}
	

	/**
	 * AbstractErrorValidator constructor.
	 * @param int $msDelay Number of ms to wait before trying again
	 * @param int $retries Maximum number of retries
	 */
	public function __construct($msDelay, $retries)
	{
		$this->config = [
			'ms-delay'	=> $msDelay,
			'retries'	=> $retries
		];
	}
	

	/**
	 * @return array ["ms-delay" => Number of ms to wait before trying again, "retries" => Maximum number of retries]
	 */
	public function config()
	{
		return $this->config;
	}


	/**
	 * @param \Exception $e
	 * @return bool
	 */
	public abstract function isHandled(\Exception $e);
}