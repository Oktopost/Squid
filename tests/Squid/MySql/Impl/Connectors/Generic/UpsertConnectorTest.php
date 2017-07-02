<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use lib\DataSet;
use lib\TDBAssert;

use PHPUnit\Framework\TestCase;


class UpdateConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $table;
	
	
	private function subject(array $data = []): UpdateConnector
	{
		$table = DataSet::table(['a', 'b', 'c'], $data);
		
		$connector = new UpdateConnector();
		$connector
			->setConnector(DataSet::connector())
			->setTable($table);
		
		$this->table = $table;
		
		return $connector;
	}
	
	private function row($a, $b, $c)
	{
		return ['a' => $a, 'b' => $b, 'c' => $c];
	}
	
	
	public function test_updateByRowFields_EmptyTable_NothingUpdated()
	{
		$subject = $this->subject();
		$res = $subject->updateByRowFields(['a'], $this->row(1, 2, 3));
		
		self::assertEquals(0, $res);
		self::assertRowCount(0, $this->table);
	}
	
	public function test_updateByRowFields_NoneMatchingRow_RowNotUpdated()
	{
		$subject = $this->subject($this->row(1, 2, 3));
		$res = $subject->updateByRowFields(['a'], $this->row(2, 2, 3));
		
		self::assertEquals(0, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowCount(1, $this->table, $this->row(1, 2, 3));
	}
	
	public function test_updateByRowFields_MatchingRow_RowUpdated()
	{
		$subject = $this->subject($this->row(1, 2, 3));
		$res = $subject->updateByRowFields(['a'], $this->row(1, 4, 6));
		
		self::assertEquals(1, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowCount(1, $this->table, $this->row(1, 4, 6));
	}
	
	public function test_updateByRowFields_NumberOfRowsUpdated_CorrectCountReturned()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 4, 6)]);
		$res = $subject->updateByRowFields(['a'], $this->row(1, 8, 9));
		
		self::assertEquals(2, $res);
	}
	
	public function test_updateByRowFields_UpdateByNumberOfFields_AllFieldsAreUsedInQuery()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 4, 5)]);
		$res = $subject->updateByRowFields(['a', 'b'], $this->row(1, 4, 9));
		
		self::assertEquals(1, $res);
		self::assertRowCount(2, $this->table);
		self::assertRowCount(1, $this->table, $this->row(1, 2, 3));
		self::assertRowCount(1, $this->table, $this->row(1, 4, 9));
	}
	
	
	public function test_updateByFields_EmptyTable_NothingUpdated()
	{
		$subject = $this->subject();
		$res = $subject->updateByFields(['a' => 1], $this->row(1, 2, 3));
		
		self::assertEquals(0, $res);
		self::assertRowCount(0, $this->table);
	}
	
	public function test_updateByFields_RowUpdated()
	{
		$subject = $this->subject($this->row(1, 2, 3));
		$res = $subject->updateByFields(['a' => 1], $this->row(4, 5, 6));
		
		self::assertEquals(1, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowCount(1, $this->table, $this->row(4, 5, 6));
	}
	
	public function test_updateByFields_OnlyReuqestedRowUpdate()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(4, 5, 6)]);
		$res = $subject->updateByFields(['a' => 1], $this->row(7, 8, 9));
		
		self::assertEquals(1, $res);
		self::assertRowCount(2, $this->table);
		self::assertRowCount(1, $this->table, $this->row(4, 5, 6));
		self::assertRowCount(1, $this->table, $this->row(7, 8, 9));
	}
	
	public function test_updateByFields_NumberOfRowsUpdated_CountIsCorrect()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 5, 6)]);
		$res = $subject->updateByFields(['a' => 1], $this->row(7, 8, 9));
		
		self::assertEquals(2, $res);
	}
	
	public function test_updateByFields_PartialUpdate()
	{
		$subject = $this->subject($this->row(1, 2, 3));
		$subject->updateByFields(['a' => 1], ['b' => 3]);
		
		self::assertRowCount(1, $this->table, $this->row(1, 3, 3));
	}
	
	public function test_updateByFields_NumberOfExpressionsUsed()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 4, 5)]);
		$subject->updateByFields(['a' => 1, 'b' => 2], ['a' => 10]);
		
		self::assertRowCount(1, $this->table, $this->row(10, 2, 3));
		self::assertRowCount(1, $this->table, $this->row(1, 4, 5));
	}
}