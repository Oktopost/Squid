<?php
namespace Squid\MySql\Impl\Command;


use Squid\Tests\TMySqlTestCase;
use PHPUnit\Framework\TestCase;


class CmdSelectTest extends TestCase
{
	use TMySqlTestCase;
	
	
	public function test_column_OneColumnPassed_OneValueReturned(): void
	{
		$table = $this->table(['a', 'b'], [
			[1, 2],
			[4, 5]
		]);
		
		
		$result = $table->select()->column('a')->queryAll();
		
		
		self::assertEquals([[1], [4]], $result);
	}
	
	public function test_column_ColumnPassedNumberOfTimes_DataReturnedTwice(): void
	{
		$table = $this->table(['a', 'b'], [
			[1, 2],
			[4, 5]
		]);
		
		
		$result = $table->select()->column('a', 'a')->queryAll();
		
		
		self::assertEquals([[1, 1], [4, 4]], $result);
	}
	
	
	public function test_column_NumberOfColumnsPassed_AllColumnsReturned(): void
	{
		$table = $this->table(['a', 'b', 'c'], [
			[1, 2, 3],
			[4, 5, 6]
		]);
		
		
		$result = $table->select()->column('a', 'c', 'b')->queryAll();
		
		
		self::assertEquals([[1, 3, 2], [4, 6, 5]], $result);
	}
	
	public function test_columns_ColumnPassedAsString_ColumnSelected()
	{
		$table = $this->table(['a'], [[1]]);
		
		
		$result = $table->select()->columns('a')->queryAll();
		
		
		self::assertEquals([[1]], $result);
	}
	
	public function test_columns_ColumnPassedAsArray_ColumnSelected()
	{
		$table = $this->table(['a'], [[1]]);
		
		
		$result = $table->select()->columns(['a'])->queryAll();
		
		
		self::assertEquals([[1]], $result);
	}
	
	public function test_columns_ColumnsPassedAsArray_AllColumnsReturned()
	{
		$table = $this->table(['a', 'b', 'c'], [
			[1, 2, 3],
			[4, 5, 6]
		]);
		
		
		$result = $table->select()->columns(['a', 'c', 'b'])->queryAll();
		
		
		self::assertEquals([[1, 3, 2], [4, 6, 5]], $result);
	}
	
	public function test_columns_TableNamePrefixUsed_CorrectColumnSelected()
	{
		$tableA = $this->table(['ID', 'col'], [1, 2]);
		$tableB = $this->table(['ID', 'col'], [1, 3]);
		$select = $this->select()
			->from($tableA->name(), 'a')
			->join($tableB->name(), 'b', 'a.ID = b.ID');
		
		
		$resultA = (clone $select)->columns(['col'],	'a')->queryAll();
		$resultB = (clone $select)->columns('col',		'b')->queryAll();
		
		
		self::assertEquals([[2]], $resultA);
		self::assertEquals([[3]], $resultB);
	}
}