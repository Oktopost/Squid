<?php
namespace Squid\MySql\Impl\Connection\Executors;


use Squid\MySql\Connection\AbstractMySqlExecuteDecorator;


class SilentErrorDecorator extends AbstractMySqlExecuteDecorator
{
	private $result = false;
	
	/** @var callable|null */
	private $callback;
	
	
	public function __construct(bool $result = false)
	{
		$this->result = $result;
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
		try
		{
			$result = parent::execute($cmd, $bind);
		}
		catch (\Exception $e)
		{
			if ($this->callback)
			{
				$callback = $this->callback;
				$callback($cmd, $bind, $e);
			}
			
			return $this->result;
		}
		
		return $result;
	}
}