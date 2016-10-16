<?php
namespace Squid\MySql\Impl\Connection;


use Squid\MySql\Config\MySqlConnectionConfig;
use Squid\MySql\Connection\IMySqlExecutor;
use Squid\MySql\Connection\IMySqlConnection;
use Squid\MySql\Connection\IMySqlExecuteDecorator;


class ConnectionBuilder
{
	/** @var IMySqlExecuteDecorator[] */
	private $decorators = [];
	
	
	/**
	 * @param IMySqlExecutor $first
	 * @return IMySqlExecutor
	 */
	private function getExecutors(IMySqlExecutor $first)
	{
		$last = $first;
		
		foreach ($this->decorators as $decorator)
		{
			$instance = clone $decorator;
			$instance->init($last);
			$last = $instance;
		}
		
		return $last;
	}
	

	/**
	 * @param IMySqlExecuteDecorator[] $decorators
	 */
	public function setDecorators(array $decorators)
	{
		$this->decorators = $decorators;
	}
	
	/**
	 * @param MySqlConnectionConfig $config
	 * @return IMySqlConnection
	 */
	public function create(MySqlConnectionConfig $config)
	{
		$connection = new MySqlConnection();
		$connection->setConfig($config);
		
		$executor = $this->getExecutors($connection);
		
		return new MySqlConnectionDecorator($connection, $executor);
	}
}