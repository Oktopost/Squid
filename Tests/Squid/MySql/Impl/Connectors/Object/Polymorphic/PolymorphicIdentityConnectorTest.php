<?php

namespace Squid\MySql\Impl\Connectors\Object\Polymorphic;


use lib\DataSet;
use lib\TDBAssert;
use lib\DummyObject;
use lib\DummyObjectB;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Impl\Connectors\Object\Generic\GenericIdentityConnector;
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
		$conn = new GenericIdentityConnector();
		$conn
			->setConnector(DataSet::connector())
			->setTable($table)
			->setPrimaryKeys('a')
			->setObjectMap($object);
		
		return $conn;
	}
	
	private function subject(array $dataA = [], array $dataB = [])
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
		
		return $subject;
	}


	/**
	 * @expectedException \Squid\Exceptions\SquidUsageException
	 */
	public function test_delete_ConnectorIsNotIGenericIdentityConnector_ExceptionThrown()
	{
		$config = new PolymorphByField();
		$subject = new PolymorphicIdentityConnector();
		$subject->setPolymorphicConfig($config);
		
		$config->addClass(DummyObject::class, new GenericObjectConnector());
		
		$subject->delete(new DummyObject());
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
		$subject = $this->subject([['a' => 1], ['a' => 2]], ['a' => 2, 'c' => 2]);
		
		$res = $subject->delete([$this->dummyA(1), $this->dummyB(2, 1)]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->tableA);
		self::assertRowCount(0, $this->tableB);
	}
	
	
	/**
	 * @expectedException \Squid\Exceptions\SquidUsageException
	 */
	public function test_insert_ConnectorIsNotIGenericIdentityConnector_ExceptionThrown()
	{
		$config = new PolymorphByField();
		$subject = new PolymorphicIdentityConnector();
		$subject->setPolymorphicConfig($config);
		
		$config->addClass(DummyObject::class, new GenericObjectConnector());
		
		$subject->insert(new DummyObject());
	}
	
	public function test_insert_ObjectInsertedIntoCorrectTable()
	{
		$subject = $this->subject();
		
		$res = $subject->insert($this->dummyB(1, 1));
		
		self::assertEquals(1, $res);
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(1, $this->tableB);
	}

	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_insert_ObjectAlreadyExists_ErrorThrown()
	{
		$subject = $this->subject(['a' => 1]);
		$subject->insert($this->dummyA(1));
	}
	
	public function test_insert_ArrayPassed()
	{
		$subject = $this->subject();
		
		$res = $subject->insert([$this->dummyA(1), $this->dummyB(2, 1), $this->dummyA(2)]);
		
		self::assertEquals(3, $res);
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
	}
	
	
	/**
	 * @expectedException \Squid\Exceptions\SquidUsageException
	 */
	public function test_upsert_ConnectorIsNotIGenericIdentityConnector_ExceptionThrown()
	{
		$config = new PolymorphByField();
		$subject = new PolymorphicIdentityConnector();
		$subject->setPolymorphicConfig($config);
		
		$config->addClass(DummyObject::class, new GenericObjectConnector());
		
		$subject->upsert(new DummyObject());
	}
	
	public function test_upsert_ObjectInsertedIntoCorrectTable()
	{
		$subject = $this->subject();
		
		$res = $subject->upsert($this->dummyB(1, 1));
		
		self::assertEquals(1, $res);
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(1, $this->tableB);
	}
	
	public function test_upsert_ObjectUpdatedIntoTheCorrectTable()
	{
		$subject = $this->subject([], ['a' => 1, 'c' => 1]);
		
		$res = $subject->upsert($this->dummyB(1, 2));
		
		self::assertEquals(2, $res);
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(1, $this->tableB);
		self::assertRowExists($this->tableB, ['c' => 2]);
	}
	
	public function test_upsert_ArrayPassed()
	{
		$subject = $this->subject(['a' => 1]);
		
		$res = $subject->upsert([$this->dummyA(1), $this->dummyB(2, 1), $this->dummyA(2)]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
	}
	
	
	/**
	 * @expectedException \Squid\Exceptions\SquidUsageException
	 */
	public function test_update_ConnectorIsNotIGenericIdentityConnector_ExceptionThrown()
	{
		$config = new PolymorphByField();
		$subject = new PolymorphicIdentityConnector();
		$subject->setPolymorphicConfig($config);
		
		$config->addClass(DummyObject::class, new GenericObjectConnector());
		
		$subject->update(new DummyObject());
	}
	
	public function test_upsert_ObjectNotInserted()
	{
		$subject = $this->subject();
		
		$res = $subject->update($this->dummyB(1, 1));
		
		self::assertEquals(0, $res);
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(0, $this->tableB);
	}
	
	public function test_upsert_ObjectUpdatedIntoCorrectTable()
	{
		$subject = $this->subject([], ['a' => 1, 'c' => 1]);
		
		$res = $subject->update($this->dummyB(1, 2));
		
		self::assertEquals(1, $res);
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(1, $this->tableB);
		self::assertRowExists($this->tableB, ['c' => 2]);
	}
}