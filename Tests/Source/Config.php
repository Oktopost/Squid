<?php
namespace SquidTest;


class Config
{
	private const DEFAULT_SQUID_ENV	= 'local';
	
	
	public const DB_NAME	= '__squid__test__';
	
	
	private static array $connectionConfig = 
	[
		'db'		=> self::DB_NAME,
		'user'		=> '_squid_test_u_',
		'password'	=> '_squid_test_pass_',
		'host'		=> 'localhost'
	];
	
	
	public static function set(array $config): void
	{
		$config['db'] = self::DB_NAME;
		self::$connectionConfig = $config;
	}
	
	
	public static function get(): array
	{
		return self::$connectionConfig;
	}
	
	public static function setup(): void
	{
		$configName = getenv('SQUID_ENV') ?: self::DEFAULT_SQUID_ENV;
		$path = __DIR__ . "/../Config/config.$configName.php";
		
		if (!$path || !file_exists($path))
		{
			throw new \Exception("Invalid path for config file: $path");
		}
		
		require_once $path;
	}
}