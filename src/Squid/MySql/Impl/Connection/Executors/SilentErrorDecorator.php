<?php
namespace Squid\MySql\Impl\Connection\Executors;


use Squid\MySql\Connection\AbstractMySqlExecuteDecorator;


class SilentErrorDecorator extends AbstractMySqlExecuteDecorator
{
	private $result = false;
	
	/** @var callable|null */
	private $callback;
	

	/**
	 * @param bool $result
	 */
	public function __construct($result = false)
	{
		$this->result = $result;
	}


	/**
	 * @param callable $callback
	 * @return static
	 */
	public function setCallback($callback)
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