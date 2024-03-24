<?php
namespace Squid\Tests;


use Squid\MySql;
use Squid\MySql\IMySqlConnector;


class MySqlTestConnection
{
	private static array|null $config;
	
	
	public static function select(): MySql\Command\ICmdSelect
	{
		return self::requireTestConnector()->select();
	}
	
	
	public static function requireTestConnector(): IMySqlConnector
	{
		if (is_null(self::$config))
			throw new \Exception('Missing Test Connector in ' . self::class);
		
		return MySql::staticConnector(self::$config);
	}
	
	public static function setTestConfig(array $config): void
	{
		self::$config = $config;
	}
	
	public static function getTestConfig(): ?array
	{
		return self::$config;
	}
	
	public static function hasTestConfig(): bool
	{
		return !is_null(self::$config);
	}
	
	public static function resetTestConfig(): void
	{
		self::$config = null;
	}
}