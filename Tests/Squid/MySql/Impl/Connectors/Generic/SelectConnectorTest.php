<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use lib\DataSet;
use lib\TDBAssert;

use PHPUnit\Framework\TestCase;


class SelectConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $table;
	
	
	private function subject(array $data = []): SelectConnector
	{
		$table = DataSet::table(['a', 'b', 'c'], $data);
		
		$connector = new SelectConnector();
		$connector
			->setConnector(DataSet::connector())
			->setTable($table);
		
		$this->table = $table;
		
		return $connector;
	}
	
	
	public function test_oneByField_NotFound_ReturnNull()
	{
		$subject = $this->subject($this->row(3, 4, 5));
		$res = $subject->oneByField('a', 1);
		self::assertNull($res);
	}
	
	public function test_oneByField_RowFoundAndReturned()
	{
		$subject = $this->subject($this->row(1, 2, 3));
		$res = $subject->oneByField('a', 1);
		self::assertEquals(self::$LAST_ROW, $res);
	}
	
	public function test_oneByField_NumberOfRowsFound_ExceptionThrown()
	{
		$this->expectException(\Squid\Exceptions\SquidException::class);
		
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 5, 6)]);
		$subject->oneByField('a', 1);
	}
	
	
	public function test_firstByField_NotFound_ReturnNull()
	{
		$subject = $this->subject($this->row(3, 4, 5));
		$res = $subject->firstByField('a', 1);
		self::assertNull($res);
	}
	
	public function test_firstByField_RowFoundAndReturned()
	{
		$subject = $this->subject($this->row(1, 2, 3));
		$res = $subject->firstByField('a', 1);
		self::assertEquals(self::$LAST_ROW, $res);
	}
	
	public function test_firstByField_NumberOfRowsFound_FirstRowReturned()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 5, 6)]);
		$res = $subject->firstByField('a', 1);
		self::assertEquals($this->row(1, 2, 3), $res);
	}
	
	
	public function test_allByField_NotFound_ReturnEmptyArray()
	{
		$subject = $this->subject($this->row(3, 4, 5));
		$res = $subject->allByField('a', 1);
		self::assertEquals([], $res);
	}
	
	public function test_allByField_RowFoundAndReturned()
	{
		$subject = $this->subject($this->row(1, 2, 3));
		$res = $subject->allByField('a', 1);
		self::assertEquals([self::$LAST_ROW], $res);
	}
	
	public function test_allByField_NumberOfRowsFound_AllRowsReturned()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 5, 6)]);
		$res = $subject->allByField('a', 1);
		self::assertEquals([$this->row(1, 2, 3), $this->row(1, 5, 6)], $res);
	}
	
	
	public function test_nByField_NotFound_ReturnEmptyArray()
	{
		$subject = $this->subject($this->row(3, 4, 5));
		$res = $subject->nByField('a', 1, 2);
		self::assertEquals([], $res);
	}
	
	public function test_nByField_LessRowsFoundThenRequested()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(2, 5, 6)]);
		$res = $subject->nByField('a', 1, 2);
		self::assertEquals([$this->row(1, 2, 3)], $res);
	}
	
	public function test_nByField_MoreRowsFoundThenRequested()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 5, 6), $this->row(1, 7, 8)]);
		$res = $subject->nByField('a', 1, 2);
		self::assertEquals([$this->row(1, 2, 3), $this->row(1, 5, 6)], $res);
	}
	
	/*********/
	
	public function test_oneByFields_NotFound_ReturnNull()
	{
		$subject = $this->subject($this->row(3, 4, 5));
		$res = $subject->oneByFields(['a' => 1, 'b' => 2]);
		self::assertNull($res);
	}
	
	public function test_oneByFields_RowFoundAndReturned()
	{
		$subject = $this->subject($this->row(1, 2, 3));
		$res = $subject->oneByFields(['a' => 1, 'b' => 2]);
		self::assertEquals(self::$LAST_ROW, $res);
	}
	
	public function test_oneByFields_NumberOfRowsFound_ExceptionThrown()
	{
		$this->expectException(\Squid\Exceptions\SquidException::class);
		
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 2, 6)]);
		$subject->oneByFields(['a' => 1, 'b' => 2]);
	}
	
	
	public function test_firstByFields_NotFound_ReturnNull()
	{
		$subject = $this->subject($this->row(3, 4, 5));
		$res = $subject->firstByFields(['a' => 1, 'b' => 2]);
		self::assertNull($res);
	}
	
	public function test_firstByFields_RowFoundAndReturned()
	{
		$subject = $this->subject($this->row(1, 2, 3));
		$res = $subject->firstByFields(['a' => 1, 'b' => 2]);
		self::assertEquals(self::$LAST_ROW, $res);
	}
	
	public function test_firstByFields_NumberOfRowsFound_FirstRowReturned()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 2, 6)]);
		$res =$subject->firstByFields(['a' => 1, 'b' => 2]);
		self::assertEquals($this->row(1, 2, 3), $res);
	}
	
	
	public function test_allByFields_NotFound_ReturnEmptyArray()
	{
		$subject = $this->subject($this->row(3, 4, 5));
		$res = $subject->allByFields(['a' => 1]);
		self::assertEquals([], $res);
	}
	
	public function test_allByFields_RowFoundAndReturned()
	{
		$subject = $this->subject($this->row(1, 2, 3));
		$res = $subject->allByFields(['a' => 1, 'b' => 2]);
		self::assertEquals([self::$LAST_ROW], $res);
	}
	
	public function test_allByFields_NumberOfRowsFound_AllRowsReturned()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 2, 6)]);
		$res = $subject->allByFields(['a' => 1, 'b' => 2]);
		self::assertEquals([$this->row(1, 2, 3), $this->row(1, 2, 6)], $res);
	}
	
	
	public function test_nByFields_NotFound_ReturnEmptyArray()
	{
		$subject = $this->subject($this->row(3, 4, 5));
		$res = $subject->nByFields(['a' => 1], 2);
		self::assertEquals([], $res);
	}
	
	public function test_nByFields_LessRowsFoundThenRequested()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(2, 5, 6)]);
		$res = $subject->nByFields(['a' => 1, 'b' => 2], 2);
		self::assertEquals([$this->row(1, 2, 3)], $res);
	}
	
	public function test_nByFields_MoreRowsFoundThenRequested()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(1, 2, 4), $this->row(1, 2, 5)]);
		$res = $subject->nByFields(['a' => 1, 'b' => 2], 2);
		self::assertEquals([$this->row(1, 2, 3), $this->row(1, 2, 4)], $res);
	}
	
	
	public function test_all_EmptyTable_ReturnEmptyArray()
	{
		$subject = $this->subject();
		$res = $subject->all();
		self::assertEquals([], $res);
	}
	
	public function test_all_AllRowsSelect()
	{
		$subject = $this->subject([$this->row(1, 2, 3), $this->row(3, 4, 5), $this->row(6, 7, 8)]);
		$res = $subject->all();
		self::assertEquals([$this->row(1, 2, 3), $this->row(3, 4, 5), $this->row(6, 7, 8)], $res);
	}
}