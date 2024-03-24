<?php
namespace SquidTest;


use Squid\MySql;
use Squid\MySql\IMySqlConnector;
use Squid\Tests\MySqlTestConnection;


class TestDB
{
	public static function reset(): void
	{
		$DB_NAME = Config::DB_NAME;
		$conn = self::connector(false);
		
		$conn->direct("DROP DATABASE IF EXISTS $DB_NAME")->exec();
		$conn->direct("CREATE DATABASE $DB_NAME DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin")->exec();
	}
	
	
	public static function connector(bool $withDB = true): IMySqlConnector
	{
		$config = Config::get();
		
		if (!$withDB)
		{
			unset($config['db']);
		}
		
		return MySql::staticConnector($config);
	}
	
	
	public static function setup(): void
	{
		self::reset();
		MySqlTestConnection::setTestConfig(Config::get());
	}
}