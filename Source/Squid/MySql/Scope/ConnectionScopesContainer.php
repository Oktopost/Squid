<?php
namespace Squid\MySql\Scope;


use Squid\MySql\Config\MySqlConnectionConfig;
use Squid\MySql\Connection\IMySqlConnection;


class ConnectionScopesContainer
{
	/** @var ConnectionScope[][] */
	private array $connections = [];
	
	
	public function get(MySqlConnectionConfig $config): ?ConnectorScopeDecorator
	{
		$hash = ConnectionScope::hashConfig($config);
		$scopes = ($this->connections[$hash] ?? []);
		
		foreach ($scopes as $scope)
		{
			if ($scope->isSame($config))
			{
				return $scope->createDecorator($config);
			}
		}
		
		return null; 
	}
	
	public function create(MySqlConnectionConfig $config, IMySqlConnection $connection): ?ConnectorScopeDecorator
	{
		$scope = new ConnectionScope($config, $connection);
		$hash = $scope->hash();
		
		$this->connections[$hash][] = $scope;
		
		return $scope->createDecorator($config);
	}
}