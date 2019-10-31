<?php
namespace Squid\MySql\Impl\Connectors\Object\Generic;


use lib\DataSet;
use lib\DummyObject;
use lib\TDBAssert;
use PHPUnit\Framework\TestCase;


class GenericIdConnectorTest extends TestCase
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
	
	private function subject(): GenericIdConnector
	{
		$this->table = DataSet::table(['a', 'b']);
		
		$connector = new GenericIdConnector();
		$connector
			->setConnector(DataSet::connector())
			->setObjectMap(DummyObject::class)
			->setIdKey('a')
			->setTable($this->table);
		
		return $connector;
	}
	
	public function test_sanity()
	{
		$subject = $this->subject();
		
		$a = $this->createObject(1, 2);
		
		self::assertEquals(1, $subject->insert($a));
		self::assertRowExists($this->table, ['a' => 1, 'b' => 2]);
		
		$b = $subject->loadById('1');
		self::assertEquals($a->toArray(), $b->toArray());
		
		$subject->deleteById(1);
		self::assertRowCount(0, $this->table);
	}
}