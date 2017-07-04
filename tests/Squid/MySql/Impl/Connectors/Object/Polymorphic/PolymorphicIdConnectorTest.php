<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic;


use lib\DataSet;
use lib\TDBAssert;
use lib\DummyObject;
use lib\DummyObjectB;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Impl\Connectors\Object\Generic\GenericIdentityConnector;
use Squid\MySql\Impl\Connectors\Object\Polymorphic\Config\PolymorphByField;


class PolymorphicIdConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $tableA;
	private $tableB;
	
	
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
		
		$config->addFieldRule('a', 
			function ($field, $value)
			{
				if ($value >= 1000)
				{
					return DummyObjectB::class;
				}
				else
				{
					return DummyObject::class;
				}
			});
		
		$subject = new PolymorphicIdConnector();
		$subject->setIdKey('a');
		$subject->setPolymorphicConfig($config);
		
		return $subject;
	}
	
	
	public function test_deleteById_CorrectTableAffected()
	{
		$subject = $this->subject(['a' => 1000, 'b' => 2], ['a' => 1000, 'b' => 3, 'c' => 4]);
		$res = $subject->deleteById(1000);
		
		self::assertEquals(1, $res);
		self::assertRowCount(1, $this->tableA);
		self::assertRowCount(0, $this->tableB);
	}
	
	public function test_deleteById_ArrayOfIdsPassed()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2]], ['a' => 1000, 'b' => 3, 'c' => 4]);
		$res = $subject->deleteById([1000, 1]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->tableA);
		self::assertRowCount(0, $this->tableB);
	}
	
	
	public function test_loadById_ObjectSelectedFromCorrectTable()
	{
		$subject = $this->subject(['a' => 1, 'b' => 2], ['a' => 1, 'b' => 3, 'c' => 4]);
		$res = $subject->loadById(1);
		
		self::assertEquals(['a' => 1, 'b' => 2], $res->toArray());
	}
	
	public function test_loadById_ArrayOfIdsPassed()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4]], ['a' => 1000, 'b' => 3, 'c' => 4]);
		$res = $subject->loadById([1, 1000]);
	
		self::assertCount(2, $res);
		self::assertEquals(['a' => 1, 'b' => 2], $res[0]->toArray());
		self::assertEquals(['a' => 1000, 'b' => 3, 'c' => 4], $res[1]->toArray());
	}
}