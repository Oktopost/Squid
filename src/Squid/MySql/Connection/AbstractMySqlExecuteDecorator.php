<?php
namespace Squid\MySql\Connection;


abstract class AbstractMySqlExecuteDecorator implements IMySqlExecuteDecorator
{
	/** @var IMySqlExecutor */
	private $child;


	/**
	 * @return IMySqlExecutor
	 */
	protected function child()
	{
		return $this->child;
	}
	
	
	/**
	 * @param IMySqlExecutor $child Decorated executor.
	 */
	public function init(IMySqlExecutor $child = null)
	{
		$this->child = $child;
	}

	/**
	 * @param string $cmd
	 * @param array $bind
	 * @return mixed
	 */
	public function execute($cmd, array $bind = [])
	{
		$this->child->execute($cmd, $bind);
	}
}