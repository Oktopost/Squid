<?php
namespace Squid\MySql\Impl\Connection\Executors;


use Squid\MySql\Connection\AbstractMySqlExecuteDecorator;


class CallbackDecorator extends AbstractMySqlExecuteDecorator
{
	/** @var callable */
	private $preCallback;
	
	/** @var callable */
	private $postCallback;


	/**
	 * @param callable $preCallback
	 * @param callable $postCallback
	 */
	public function __construct($preCallback = null, $postCallback = null)
	{
		$this->preCallback = $preCallback;
		$this->postCallback = $postCallback;
	}


	/**
	 * @param callable $callback In format function(string $cmd, array $bind = [])
	 * @return static
	 */
	public function setPreExecuteCallback($callback)
	{
		$this->preCallback = $callback;
		return $this;
	}

	/**
	 * @param callable $callback In format function(mixed $result)
	 * @return static
	 */
	public function setPostExecuteCallback($callback)
	{
		$this->postCallback = $callback;
		return $this;
	}
	
	
	/**
	 * @param string $cmd
	 * @param array $bind
	 * @return mixed
	 */
	public function execute($cmd, array $bind = [])
	{
		if ($this->preCallback)
		{
			$preCallback = $this->preCallback;
			$preCallback($cmd, $bind);
		}
		
		$result = parent::execute($cmd, $bind);
		
		if ($this->postCallback)
		{
			$postCallback = $this->postCallback;
			$postCallback($result);
		}
		
		return $result;
	}
}