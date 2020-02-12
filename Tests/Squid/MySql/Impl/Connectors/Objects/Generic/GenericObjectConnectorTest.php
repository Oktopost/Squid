<?php
namespace Squid\MySql\Impl\Connectors\Objects\Generic;


use lib\DataSet;
use lib\DummyObject;
use lib\TDBAssert;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Connectors\Objects\Query\ICmdObjectSelect;


class GenericObjectConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $table;
	
	
	private function createObject($a, $b): DummyObject
	{
		$result = new DummyObject();
		
		$result->a = $a;
		$result->b = $b;
		
		return $result;
	}
	
	private function subject()
	{
		$this->table = DataSet::table(['a', 'b']);
		
		$connector = new GenericObjectConnector();
		$connector
			->setConnector(DataSet::connector())
			->setObjectMap(DummyObject::class)
			->setTable($this->table);
		
		return $connector;
	}
	
	
	public function test_sanity()
	{
		$subject = $this->subject();
		
		$a = $this->createObject(1, 2);
		
		$subject->insertObjects($a);
		self::assertRowExists($this->table, ['a' => 1, 'b' => 2]);
		
		$b = $subject->selectFirstObjectByFields(['b' => 2]);
		self::assertEquals($a->toArray(), $b->toArray());
		
		$subject->deleteByField('a', '1');
		self::assertRowCount(0, $this->table);
	}
	
	
	public function test_query_ICmdObjectSelectInstanceReturned()
	{
		self::assertInstanceOf(ICmdObjectSelect::class, $this->subject()->query());
	}
	
	public function test_query_sanity()
	{
		$subject = $this->subject();
		
		$a = $this->createObject(1, 2);
		$subject->insertObjects($a);
		$result = $subject->query()->byField('a', $a->a)->queryFirst();
		
		self::assertInstanceOf(DummyObject::class, $result);
		self::assertEquals(['a' => 1, 'b' => 2], $result->toArray());
	}
}