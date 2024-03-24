<?php
namespace Squid\MySql\Impl\Connectors\Objects\Polymorphic;


use lib\DataSet;
use lib\TDBAssert;
use lib\DummyObject;
use lib\DummyObjectB;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Impl\Connectors\Objects\Generic\GenericIdentityConnector;
use Squid\MySql\Impl\Connectors\Objects\Polymorphic\Config\PolymorphByField;


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
			->direct("ALTER TABLE {$tableA} ADD PRIMARY KEY (a), CHANGE `a` `a` INT(11) NOT NULL AUTO_INCREMENT")->executeDml();
		
		DataSet::connector()
			->direct("ALTER TABLE {$tableB} ADD PRIMARY KEY (a), CHANGE `a` `a` INT(11) NOT NULL AUTO_INCREMENT")->executeDml();
		
		
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
	
	
	public function test_save()
	{
		$subject = $this->subject(['a' => 1, 'b' => 1], ['a' => 1000, 'b' => 1, 'c' => 1]);
		
		$res = $subject->save([
			new DummyObject(['a' => 1, 'b' => -1]),
			new DummyObject(['a' => null, 'b' => 2]),
			new DummyObjectB(['a' => 1000, 'b' => 2, 'c' => 3]),
			new DummyObjectB(['a' => null, 'b' => 4, 'c' => 5]),
		]);
		
		self::assertEquals(6, $res);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(2, $this->tableB);
		
		self::assertRowExists($this->tableA, ['b' => -1]);
		self::assertRowExists($this->tableA, ['b' => 2]);
		
		self::assertRowExists($this->tableB, ['b' => 2, 'c' => 3]);
		self::assertRowExists($this->tableB, ['b' => 4, 'c' => 5]);
	}
}