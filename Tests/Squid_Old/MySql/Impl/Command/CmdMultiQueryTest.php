<?php
namespace Squid\MySql\Impl\Command;


use lib\DataSet;
use lib\TestTable;

use PHPUnit\Framework\TestCase;


class CmdMultiQueryTest extends TestCase
{
	/**
	 * @param TestTable $table
	 * @return \Squid\MySql\Command\ICmdInsert
	 */
	private function insert(TestTable $table = null, array $data = [])
	{
		$insert = DataSet::connector()
			->insert()
			->into($table)
			->values($data);
		
		return $insert;
	}
	
	private function insertString(TestTable $table = null, array $data = []): string
	{
		$insert = DataSet::connector()
			->insert()
			->into($table)
			->values($data);
		
		return $insert->assemble();
	}

	/**
	 * @return \Squid\MySql\Command\ICmdMultiQuery
	 */
	private function bulk()
	{
		return DataSet::connector()
			->bulk();
	}
	
	
	public function test_bulk_TwoMySqlCommandConstructorAdded()
	{
		$table = DataSet::table(['a', 'b', 'c'], [
			[1, 2, 3],
			[4, 5, 6]
		]);
		
		$this->bulk()
			->add($this->insert($table, [7, 7, 7]))
			->add($this->insert($table, [6, 6, 6]))
			->executeAll();
				
		$firstRow = DataSet::connector()
			->select()
			->from($table)
			->byFields(['a', 'b', 'c'], [7, 7, 7])
			->queryCount();
		
		$secondRow = DataSet::connector()
			->select()
			->from($table)
			->byFields(['a', 'b', 'c'], [6, 6, 6])
			->queryCount();
		
		self::assertEquals(1, $firstRow);
		self::assertEquals(1, $secondRow);
	}
	
	public function test_bulk_TwoMySqlCommandConstructorAddedAsArray()
	{
		$table = DataSet::table(['a', 'b', 'c'], [
			[1, 2, 3],
			[4, 5, 6]
		]);
		
		$this->bulk()
			->add([$this->insert($table, [7, 7, 7]), $this->insert($table, [6, 6, 6])])
			->executeAll();
				
		$firstRow = DataSet::connector()
			->select()
			->from($table)
			->byFields(['a', 'b', 'c'], [7, 7, 7])
			->queryCount();
		
		$secondRow = DataSet::connector()
			->select()
			->from($table)
			->byFields(['a', 'b', 'c'], [6, 6, 6])
			->queryCount();
		
		self::assertEquals(1, $firstRow);
		self::assertEquals(1, $secondRow);
	}
	
	public function test_bulk_TwoStringsAdded()
	{
		$table = DataSet::table(['a', 'b', 'c'], [
			[1, 2, 3],
			[4, 5, 6]
		]);
		
		$this->bulk()
			->add($this->insertString($table, [7, 7, 7]), [7, 7, 7])
			->add($this->insertString($table, [6, 6, 6]), [6, 6, 6])
			->executeAll();
				
		$firstRow = DataSet::connector()
			->select()
			->from($table)
			->byFields(['a', 'b', 'c'], [7, 7, 7])
			->queryCount();
		
		$secondRow = DataSet::connector()
			->select()
			->from($table)
			->byFields(['a', 'b', 'c'], [6, 6, 6])
			->queryCount();
		
		self::assertEquals(1, $firstRow);
		self::assertEquals(1, $secondRow);
	}
	
	public function test_bulk_DifferentSourceAdded()
	{
		$table = DataSet::table(['a', 'b', 'c'], [
			[1, 2, 3],
			[4, 5, 6]
		]);
		
		$this->bulk()
			->add($this->insert($table, [7, 7, 7]))
			->add($this->insertString($table, [6, 6, 6]), [6, 6, 6])
			->executeAll();
				
		$firstRow = DataSet::connector()
			->select()
			->from($table)
			->byFields(['a', 'b', 'c'], [7, 7, 7])
			->queryCount();
		
		$secondRow = DataSet::connector()
			->select()
			->from($table)
			->byFields(['a', 'b', 'c'], [6, 6, 6])
			->queryCount();
		
		self::assertEquals(1, $firstRow);
		self::assertEquals(1, $secondRow);
	}
}