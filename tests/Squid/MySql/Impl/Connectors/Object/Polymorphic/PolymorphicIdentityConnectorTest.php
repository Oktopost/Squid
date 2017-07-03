<?php

namespace Squid\MySql\Impl\Connectors\Object\Polymorphic;


use lib\DataSet;
use lib\TDBAssert;
use lib\DummyObject;
use lib\DummyObjectB;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Impl\Connectors\Object\Generic\GenericObjectConnector;
use Squid\MySql\Impl\Connectors\Object\Polymorphic\Config\PolymorphByField;


class PolymorphicIdentityConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $tableA;
	private $tableB;
	
	
	private function dummyA($a) : DummyObject
	{
		return new DummyObject(['a' => $a, 'b' => 'DummyA']);
	}
	
	private function dummyB($a, $c = 1) : DummyObjectB
	{
		return new DummyObjectB(['a' => $a, 'b' => 'DummyB', 'c' => $c]);
	}
	
	
	private function createObjectConnector($table, $object)
	{
		$conn = new GenericObjectConnector();
		$conn
			->setConnector(DataSet::connector())
			->setTable($table)
			->setObjectMap($object);
		
		return $conn;
	}
	
	private function subject(array $dataA = [], array $dataB = [], $keys = ['a'])
	{
		if ($dataA && !isset($dataA[0]))
			$dataA = [$dataA];
		
		if ($dataB && !isset($dataB[0]))
			$dataB = [$dataB];
		
		foreach ($dataA as &$value)
		{
			$value['b'] = 'DummyA';
		}
		
		foreach ($dataB as &$value)
		{
			$value['b'] = 'DummyB';
		}
		
		$tableA = DataSet::table(['a', 'b'], $dataA);
		$tableB = DataSet::table(['a', 'b', 'c'], $dataB);
		
		$this->tableA = $tableA;
		$this->tableB = $tableB;
		
		DataSet::connector()
			->direct("ALTER TABLE {$tableA} ADD PRIMARY KEY (a), CHANGE `a` `a` INT(11)")->executeDml();
		
		DataSet::connector()
			->direct("ALTER TABLE {$tableB} ADD PRIMARY KEY (a), CHANGE `a` `a` INT(11)")->executeDml();
		
		
		$config = new PolymorphByField();
		$config->addClass([
			DummyObject::class		=> $this->createObjectConnector($tableA, DummyObject::class),
			DummyObjectB::class		=> $this->createObjectConnector($tableB, DummyObjectB::class),
		]);
		
		$config->addFieldRule('b', 
			[
				'DummyA' => DummyObject::class,
				'DummyB' => DummyObjectB::class,
			]);
		
		$subject = new PolymorphicIdentityConnector();
		$subject->setPolymorphicConfig($config);
		$subject->setPrimaryKeys($keys);
		
		return $subject;
	}
	
	
	public function test_delete_OnlyRequestTableAffected()
	{
		$subject = $this->subject(['a' => 1], ['a' => 1, 'c' => 2]);
		
		$res = $subject->delete($this->dummyB(1, 1));
		
		self::assertEquals(1, $res);
		self::assertRowCount(1, $this->tableA);
		self::assertRowCount(0, $this->tableB);
	}
	
	public function test_delete_ArrayPassed()
	{
		$subject = $this->subject(['a' => 1], ['a' => 2, 'c' => 2]);
		
		$res = $subject->delete([$this->dummyA(1), $this->dummyB(2, 1)]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(0, $this->tableB);
	}
	
	public function test_delete_ArrayKeys()
	{
		$subject = $this->subject(['a' => 1], ['a' => 2, 'c' => 2], ['a', 'b']);
		
		$res = $subject->delete([$this->dummyA(1), $this->dummyB(2, 1)]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(0, $this->tableB);
	}
	
	
	public function test_sanity()
	{
		$subject = $this->subject(['a' => 1], ['a' => 1, 'c' => 2]);
		$objectA = $this->dummyA(2);
		$objectB = $this->dummyB(1, 4);
		
		$res = $subject->insert($objectA);
		
		self::assertEquals(1, $res);
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
		
		$res = $subject->update($objectB);
		
		self::assertEquals(1, $res);
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
		self::assertRowExists($this->tableB, ['c' => 4]);
	}
}