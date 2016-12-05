<?php
namespace Squid\MySql\Impl\Command;


use lib\DataSet;
use lib\TestTable;


class CmdSelectTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @param TestTable $table
	 * @return \Squid\MySql\Command\ICmdSelect
	 */
	private function select(TestTable $table = null)
	{
		$select = DataSet::connector()->select();
		
		if ($table)
		{
			$select->from($table);
		}
		
		return $select;
	}
	
	
	public function test_column_OneColumnPassed()
	{
		$table = DataSet::table(__FUNCTION__, ['a', 'b', 'c'], [
			[1, 2, 3],
			[4, 5, 6]
		]);
		
		$result = $this->select($table)->column('a')->queryAll();
		
		$this->assertEquals([[1], [4]], $result);
	}
	
	public function test_column_ColumnPassedNumberOfTimes()
	{
		$table = DataSet::table(__FUNCTION__, ['a', 'b', 'c'], [
			[1, 2, 3],
			[4, 5, 6]
		]);
		
		$result = $this->select($table)->column('a', 'a')->queryAll();
		
		$this->assertEquals([[1, 1], [4, 4]], $result);
	}
	
	public function test_column_NumberOfColumnsPassed()
	{
		$table = DataSet::table(__FUNCTION__, ['a', 'b', 'c'], [
			[1, 2, 3],
			[4, 5, 6]
		]);
		
		$result = $this->select($table)->column('a', 'c', 'b')->queryAll();
		
		$this->assertEquals([[1, 3, 2], [4, 6, 5]], $result);
	}
	
	
	public function test_columns_OneColumnPassed()
	{
		$table = DataSet::table(__FUNCTION__, ['a', 'b'], [1, 2]);
		
		$result = $this->select($table)->columns(['a'])->queryAll();
		
		$this->assertEquals([[1]], $result);
	}
	
	public function test_columns_OneColumnPassedAsSingleString()
	{
		$table = DataSet::table(__FUNCTION__, ['a', 'b'], [1, 2]);
		
		$result = $this->select($table)->columns('a')->queryAll();
		
		$this->assertEquals([[1]], $result);
	}
	
	public function test_columns_NumberOfColumnsPassed()
	{
		$table = DataSet::table(__FUNCTION__, ['a', 'b'], [1, 2]);
		
		$result = $this->select($table)->columns(['a', 'b'])->queryAll();
		
		$this->assertEquals([[1, 2]], $result);
	}
	
	public function test_columns_TableNamePrefixUsed()
	{
		$tableA = DataSet::table(__FUNCTION__, ['ID', 'col'], [1, 2]);
		$tableB = DataSet::table(__FUNCTION__, ['ID', 'col'], [1, 3]);
		
		$result = $this->select()
			->columns(['col'], 'a')
			->from($tableA, 'a')
			->join($tableB, 'b', 'a.ID = b.ID')
			->queryAll();
		
		$this->assertEquals([[2]], $result);
	}
	
	public function test_columns_TableNamePrefixWithStringColumn()
	{
		$tableA = DataSet::table(__FUNCTION__, ['ID', 'col'], [1, 2]);
		$tableB = DataSet::table(__FUNCTION__, ['ID', 'col'], [1, 3]);
		
		$result = $this->select()
			->columns('col', 'a')
			->from($tableA, 'a')
			->join($tableB, 'b', 'a.ID = b.ID')
			->queryAll();
		
		$this->assertEquals([[2]], $result);
	}
}