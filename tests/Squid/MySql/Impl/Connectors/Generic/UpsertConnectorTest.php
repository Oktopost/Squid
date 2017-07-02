<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use lib\DataSet;
use lib\TDBAssert;

use PHPUnit\Framework\TestCase;


class UpsertConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $table;
	
	
	private function subject(array $data = []): UpsertConnector
	{
		$table = DataSet::table(['a', 'b', 'c'], $data);
		
		$connector = new UpsertConnector();
		$connector
			->setConnector(DataSet::connector())
			->setTable($table);
		
		DataSet::connector()
			->direct("ALTER TABLE $table ADD PRIMARY KEY (a), CHANGE `a` `a` INT(11) NOT NULL AUTO_INCREMENT")
			->executeDml();
		
		$this->table = $table;
		
		return $connector;
	}
	
	
	public function test_upsertByKeys_EmptyTable_RowInserted()
	{
		$subject = $this->subject();
		$res = $subject->upsertByKeys(['a'], self::row(1, 2, 3));
		
		self::assertEquals(1, $res);
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	public function test_upsertByKeys_NoneMatchingRow_NewRowInserted()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertByKeys(['a'], self::row(2, 2, 3));
		
		self::assertEquals(1, $res);
		self::assertRowCount(2, $this->table);
		self::assertRowCount(1, $this->table, self::row(1, 2, 3));
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	public function test_upsertByKeys_MatchingRow_RowUpdated()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertByKeys(['a'], self::row(1, 4, 6));
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	public function test_upsertByKeys_UseSingleStringAsKey()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertByKeys('a', self::row(1, 4, 6));
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	
	public function test_upsertAllByKeys_EmptyTable_RowInserted()
	{
		$subject = $this->subject();
		$res = $subject->upsertAllByKeys(['a'], [self::row(1, 2, 3), self::row(4, 5, 6)]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->table, self::row(1, 2, 3));
		self::assertRowCount(1, $this->table, self::row(4, 5, 6));
	}
	
	public function test_upsertAllByKeys_NoneMatchingRow_NewRowInserted()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertAllByKeys(['a'], [self::row(4, 5, 6)]);
		
		self::assertEquals(1, $res);
		self::assertRowCount(2, $this->table);
		self::assertRowCount(1, $this->table, self::row(1, 2, 3));
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	public function test_upsertAllByKeys_MatchingRow_RowUpdated()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertAllByKeys(['a'], [self::row(1, 4, 6)]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	public function test_upsertAllByKeys_UseSingleStringAsKey()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertAllByKeys('a', [self::row(1, 4, 6)]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	
	public function test_upsertByValues_EmptyTable_RowInserted()
	{
		$subject = $this->subject();
		$res = $subject->upsertByValues(['b', 'c'], self::row(1, 2, 3));
		
		self::assertEquals(1, $res);
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	public function test_upsertByValues_NoneMatchingRow_NewRowInserted()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertByValues(['b', 'c'], self::row(2, 2, 3));
		
		self::assertEquals(1, $res);
		self::assertRowCount(2, $this->table);
		self::assertRowCount(1, $this->table, self::row(1, 2, 3));
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	public function test_upsertByValues_MatchingRow_RowUpdated()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertByValues(['b', 'c'], self::row(1, 4, 6));
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	public function test_upsertByValues_UseSingleStringAsKey()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertByValues('b', self::row(1, 4, 6));
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowCount(1, $this->table, self::row(1, 4, 3));
	}
	
	
	public function test_upsertAllByValues_EmptyTable_RowInserted()
	{
		$subject = $this->subject();
		$res = $subject->upsertAllByValues(['b', 'c'], [self::row(1, 2, 3), self::row(4, 5, 6)]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->table, self::row(1, 2, 3));
		self::assertRowCount(1, $this->table, self::row(4, 5, 6));
	}
	
	public function test_upsertAllByValues_NoneMatchingRow_NewRowInserted()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertAllByValues(['b', 'c'], [self::row(4, 5, 6)]);
		
		self::assertEquals(1, $res);
		self::assertRowCount(2, $this->table);
		self::assertRowCount(1, $this->table, self::row(1, 2, 3));
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	public function test_upsertAllByValues_MatchingRow_RowUpdated()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertAllByValues(['b', 'c'], [self::row(1, 4, 6)]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowCount(1, $this->table, self::$LAST_ROW);
	}
	
	public function test_upsertAllByValues_UseSingleStringAsKey()
	{
		$subject = $this->subject(self::row(1, 2, 3));
		$res = $subject->upsertAllByValues('b', [self::row(1, 4, 6)]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowCount(1, $this->table, self::row(1, 4, 3));
	}
}