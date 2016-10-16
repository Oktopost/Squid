<?php
namespace Squid\MySql\Impl\Connection\Executors;


use Squid\MySql\Connection\AbstractMySqlExecuteDecorator;


class SilentErrorDecorator extends AbstractMySqlExecuteDecorator
{
	private $result = false;


	/**
	 * @param bool $result
	 */
	public function __construct($result = false)
	{
		$this->result = $result;
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
			return $this->result;
		}
		
		return $result;
	}
}