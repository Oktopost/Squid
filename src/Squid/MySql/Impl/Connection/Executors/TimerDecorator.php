<?php
namespace Squid\MySql\Impl\Connection\Executors;


use Squid\Exceptions\SquidException;
use Squid\MySql\Connection\AbstractMySqlExecuteDecorator;


class TimerDecorator extends AbstractMySqlExecuteDecorator
{
	/** @var callable */
	private $callback = null;
	
	/** @var double */
	private $unixStartTime;


	/**
	 * @param string $cmd
	 * @param array $bind
	 * @param $runTime
	 */
	protected function measure($cmd, array $bind = [], $runTime)
	{
		if (!$this->callback)
			throw new SquidException(
				'When using TimerDecorator you should override measure ' . 
				'method or provide a callback function!');
		
		$callback = $this->callback;
		$callback($cmd, $bind, $runTime);
	}
	
	
	/**
	 * @param callable $callback Ignore if overriding measure() method.
	 */
	public function __construct(callable $callback = null)
	{
		$this->callback = $callback;
	}

	
	/**
	 * @param callable $callback
	 * @return static
	 */
	public function setCallback(callable $callback)
	{
		$this->callback = $callback;
		return $this;
	}
	
	/**
	 * @param string $cmd
	 * @param array $bind
	 * @return mixed
	 */
	public function execute($cmd, array $bind = [])
	{
		$this->unixStartTime = microtime(true);
		$result = parent::execute($cmd, $bind);
		$this->measure($cmd, $bind, microtime(true) - $this->unixStartTime);
		
		return $result;
	}
}