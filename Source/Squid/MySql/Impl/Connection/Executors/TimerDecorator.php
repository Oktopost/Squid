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
	
	
	protected function measure(string $cmd, array $bind = [], $runTime): void
	{
		if (!$this->callback)
			throw new SquidException(
				'When using TimerDecorator you should override measure ' . 
				'method or provide a callback function!');
		
		$callback = $this->callback;
		$callback($cmd, $bind, $runTime);
	}
	
	
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
	
	public function execute(string $cmd, array $bind = [])
	{
		$this->unixStartTime = microtime(true);
		$result = parent::execute($cmd, $bind);
		$this->measure($cmd, $bind, microtime(true) - $this->unixStartTime);
		
		return $result;
	}
}