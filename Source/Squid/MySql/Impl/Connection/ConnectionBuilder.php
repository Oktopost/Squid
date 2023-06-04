<?php
namespace Squid\MySql\Impl\Connection;


use Squid\MySql\Scope\ConnectionScopesContainer;
use Squid\MySql\Config\MySqlConnectionConfig;
use Squid\MySql\Connection\IMySqlExecutor;
use Squid\MySql\Connection\IMySqlConnection;
use Squid\MySql\Connection\IMySqlExecuteDecorator;


class ConnectionBuilder
{
	private static ?ConnectionScopesContainer $container = null;
	
	/** @var IMySqlExecuteDecorator[] */
	private array $decorators = [];
	
	
	private static function getConnectionScopeContainer(): ConnectionScopesContainer
	{
		if (!self::$container)
			self::$container = new ConnectionScopesContainer();
		
		return self::$container;
	}
	
	private function store(MySqlConnectionConfig $config, IMySqlConnection $connection): void
	{
		self::getConnectionScopeContainer()->create($config, $connection);
	}
	
	private function getExecutors(IMySqlExecutor $first): IMySqlExecutor
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
	
	private function createNew(MySqlConnectionConfig $config): IMySqlConnection
	{
		$connection = new MySqlConnection($config);
		$executor = $this->getExecutors($connection);
		
		return new MySqlConnectionDecorator($connection, $executor);
	}
	
	private function createOrReuse(MySqlConnectionConfig $config): IMySqlConnection
	{
		$container = self::getConnectionScopeContainer();
		$scopeDecorator = $container->get($config);
		
		if (!$scopeDecorator)
		{
			$scopeDecorator = $container->create($config, new MySqlConnection($config));
		}
		
		$connection = $scopeDecorator->connection();
		
		$executor = $this->getExecutors($connection);
		$scopeDecorator->init($executor);
		
		return new MySqlConnectionDecorator($connection, $scopeDecorator);
	}
	
	
	/**
	 * @param IMySqlExecuteDecorator[] $decorators
	 */
	public function setDecorators(array $decorators): void
	{
		$this->decorators = $decorators;
	}
	
	/**
	 * @param IMySqlExecuteDecorator[] $decorators
	 */
	public function addDecorators(array $decorators): void
	{
		$this->decorators = array_merge($this->decorators, $decorators);
	}
	
	public function create(MySqlConnectionConfig $config): IMySqlConnection
	{
		if ($config->ReuseConnection)
		{
			return $this->createOrReuse($config);
		}
		else
		{
			return $this->createNew($config);
		}
	}
}