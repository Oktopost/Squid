<?php
namespace lib;


use Squid\MySql;


class DataSet
{
	use \Traitor\TStaticClass;
	
	
	const TABLE_PREFIX = 'st_';
	
	
	/** @var TestTable[] */
	private static $tables = [];
	
	/** @var  MySql */
	private static $mysql;
	
	
	/**
	 * @param string $name
	 * @param array $columns
	 */
	private static function createTable($name, array $columns)
	{
		$columnExpression = 'VARCHAR(64) NOT NULL';
		$columnsExpression = [];
		$createTableExpression = 'CREATE TEMPORARY TABLE `' . $name . '`';
		
		foreach ($columns as $column)
		{
			$columnsExpression[] = "$column $columnExpression";
		}
		
		$columnsExpressionString = implode(', ', $columnsExpression);
		
		$dml = "$createTableExpression ($columnsExpressionString) ENGINE = InnoDB";
		
		self::$mysql->getConnector()->direct()->command($dml)->executeDml();
	}
	
	/**
	 * @param string $prefix
	 * @return string
	 */
	private static function getRandomName($prefix)
	{
		if ($prefix) $prefix = $prefix . '_';
		
		$name = '';
		
		if (strlen($prefix) > 50)
			$prefix = substr($prefix, strlen($prefix) - 50);
		
		while (!$name || isset(self::$tables[$name]))
		{
			$name = self::TABLE_PREFIX . $prefix . rand(0, 1000000);
		}
		
		return $name;
	}
	
	
	/**
	 * @param string $prefix
	 * @param array $columns
	 * @param array $data
	 * @return TestTable
	 */
	public static function tableAs($prefix, array $columns, array $data = [])
	{
		$name = self::getRandomName($prefix);
		
		self::createTable($name, $columns);
		$table = new TestTable(self::$mysql->getConnector(), $name, $columns);
		self::$tables[$name] = $table;
		
		if ($data)
			$table->data($data);
		
		return $table;
	}
	
	/**
	 * @param array $columns
	 * @param array $data
	 * @return TestTable
	 */
	public static function table(array $columns, array $data = [])
	{
		return self::tableAs('', $columns, $data);
	}
	
	/**
	 * @return MySql\IMySqlConnector
	 */
	public static function connector()
	{
		return self::$mysql->getConnector();
	}
	
	public static function clearDB()
	{
		$conn = self::$mysql->getConnector();
		$tables = $conn->db()->listTables();
		$tables = array_filter($tables, function($value) { return strpos($value, self::TABLE_PREFIX) === 0; });
		
		foreach ($tables as $table)
		{
			$conn->db()->dropTable($table);
		}
	}
	
	public static function setup()
	{
		self::$mysql = new MySql();
		self::$mysql->config()->setConfig(Config::get());
		self::clearDB();
	}
}