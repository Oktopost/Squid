<?php
namespace Squid\MySql\Impl\Connectors\Object\Generic;


use lib\DataSet;
use lib\DummyObject;
use lib\TDBAssert;
use PHPUnit\Framework\TestCase;


class GenericIdentityConnectorTest extends TestCase
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
	
	private function subject(): GenericIdentityConnector
	{
		$this->table = DataSet::table(['a', 'b']);
		
		$connector = new GenericIdentityConnector();
		$connector
			->setConnector(DataSet::connector())
			->setObjectMap(DummyObject::class)
			->setPrimaryKeys(['a'])
			->setTable($this->table);
		
		return $connector;
	}
	
	public function test_sanity()
	{
		$subject = $this->subject();
		
		$a = $this->createObject(1, 2);
		
		$subject->insert($a);
		self::assertRowExists($this->table, ['a' => 1, 'b' => 2]);
		
		$a->b = 4;
		self::assertEquals(1, $subject->update($a));
		self::assertRowExists($this->table, ['a' => 1, 'b' => 4]);
		
		$subject->delete($a);
		self::assertRowCount(0, $this->table);
	}
}