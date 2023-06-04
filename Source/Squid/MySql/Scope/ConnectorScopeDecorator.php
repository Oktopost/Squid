<?php
namespace Squid\MySql\Scope;


use Squid\MySql\Config\MySqlConnectionConfig;
use Squid\MySql\Connection\IMySqlConnection;
use Squid\MySql\Connection\AbstractMySqlExecuteDecorator;


class ConnectorScopeDecorator extends AbstractMySqlExecuteDecorator
{
	private MySqlConnectionConfig $config;
	private ConnectionScope $scope;
	
	
	private function shouldSwitch(): bool
	{
		return $this->config->DB && !$this->scope->is($this->config->DB);
	}
	
	private function switch(): void
	{
		$this->scope->set($this->config);
		parent::execute("USE `{$this->config->DB}`");
	}
	
	
	public function __construct(
		MySqlConnectionConfig $config,
		ConnectionScope $scope)
	{
		$this->config = $config;
		$this->scope = $scope;
	}
	
	
	public function connection(): IMySqlConnection
	{
		return $this->scope->connection();
	}
	
	public function execute($cmd, array $bind = [])
	{
		if ($this->shouldSwitch())
		{
			$this->switch();
		}
		
		return parent::execute($cmd, $bind);
	}
}