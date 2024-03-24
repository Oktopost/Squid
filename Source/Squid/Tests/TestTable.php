<?php
namespace Squid\Tests;


use Squid\MySql\Command\ICmdInsert;
use Squid\MySql\IMySqlConnector;
use Squid\MySql\Command\ICmdSelect;

use Structura\Arrays;
use Structura\Random;


class TestTable
{
	private static int $tableIndex = 1;
	
	
	private string			$name;
	private IMySqlConnector	$conn;
	private array			$columns;
	
	
	public function __construct(IMySqlConnector $conn, string $name, array $columns)
	{
		if (!$columns)
			throw new \Exception('At least one column must be set in ' . self::class);
			
		$this->conn = $conn;
		$this->name = $name;
		$this->columns = $columns;
	}
	
	
	public function columns(): array
	{
		return $this->columns;
	}
	
	public function columnsCount(): int
	{
		return count($this->columns);
	}
	
	public function firstColumn(): string
	{
		return $this->columns[0];
	}
	
	public function conn(): IMySqlConnector
	{
		return $this->conn;
	}
	
	public function name(): string
	{
		return $this->name;
	}
	
	public function drop(): void
	{
		$this->conn->direct("DROP TABLE {$this->name}")->exec();
	}
	
	public function insert(?array $fields = null): ICmdInsert
	{
		return $this->conn->insert()->into($this->name, $fields);
	}
	
	public function select(): ICmdSelect
	{
		return $this->conn->select()->from($this->name);
	}
	
	public function count(): int
	{
		return $this->select()->queryCount();
	}
	
	public function create(): void
	{
		$columns = [];
		
		foreach ($this->columns as $column)
		{
			$columns[] = "$column VARCHAR(64) NOT NULL";
		}
		
		$columnsExpression = implode(', ', $columns);
		
		$this->conn->direct()->command("CREATE TABLE `{$this->name}` ($columnsExpression) ENGINE = InnoDB")->exec();
	}
	
	public function insertData(array $data): void
	{
		if (!$data)
			return;
		
		if (!is_array(Arrays::first($data)))
		{
			$firstRow = $data;
			$data = [$data];
		}
		else
		{
			$firstRow = Arrays::first($data);
		}
		
		if (Arrays::isAssoc($firstRow))
		{
			$this->insert()->valuesBulk($data)->exec();
		}
		else
		{
			$this->insert($this->columns)->valuesBulk($data)->exec();
		}
	}
	
	public function isEmpty(): bool
	{
		return $this->select()->queryCount() == 0;
	}
	
	
	public static function get(
		?IMySqlConnector $conn = null, 
		?string $name = null, 
		array $fields = ['test_field']): TestTable
	{
		if (!$name)
		{
			$random = Random::string(10);
			$index = self::$tableIndex++;
			$name = "TestTable_{$index}_{$random}";
		}
		
		if (!$conn)
		{
			$conn = MySqlTestConnection::requireTestConnector();
		}
		
		$t = new TestTable($conn, $name, $fields);
		
		$t->create();
		
		return $t;
	}
}