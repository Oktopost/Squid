<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic;


use lib\DataSet;
use lib\TDBAssert;
use lib\DummyObject;
use lib\DummyObjectB;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Impl\Connectors\Object\Generic\GenericObjectConnector;
use Squid\MySql\Impl\Connectors\Object\Polymorphic\Config\PolymorphByField;


class PolymorphicConnectorTest extends TestCase
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
		
		$subject = new PolymorphicConnector();
		$subject->setPolymorphicConfig($config);
		
		return $subject;
	}
	
	
	public function test_setPolymorphicConfig_ReturnSelf()
	{
		$subject = new PolymorphicConnector();
		self::assertEquals($subject, $subject->setPolymorphicConfig(new PolymorphByField()));
	}
	
	
	public function test_countByField_FieldMatchingBothTables_AllTablesCounted()
	{
		$subject = $this->subject(
			['a' => 1],
			[
				['a' => 2, 'c' => 3],
				['a' => 3, 'c' => 6]
			]);
		
		$result = $subject->countByField('a', [1, 2, 3]);
		
		self::assertEquals(3, $result);
	}
	
	public function test_countByField_FieldNotMatchingSomeTables()
	{
		$subject = $this->subject(
			['a' => 1],
			['a' => 2, 'c' => 3]);
		
		$result = $subject->countByField('b', 'DummyA');
		
		self::assertEquals(1, $result);
	}
	
	
	public function test_countByFields_QueryMatchingBothTables()
	{
		$subject = $this->subject(
			['a' => 1],
			[
				['a' => 1, 'c' => 3],
				['a' => 2, 'c' => 3]
			]);
		
		$result = $subject->countByFields(['a' => 1]);
		
		self::assertEquals(2, $result);
	}
	
	public function test_countByFields_QueryMatchingSomeTables()
	{
		$subject = $this->subject(
			['a' => 1],
			['a' => 2, 'c' => 3]);
		
		$result = $subject->countByFields(['b' => 'DummyB', 'c' => 3]);
		
		self::assertEquals(1, $result);
	}
	
	
	public function test_existsByField_ObjectNotInTables_ReturnFalse()
	{
		$subject = $this->subject();
		
		$result = $subject->existsByField('a', [1, 2]);
		
		self::assertFalse($result);
	}
	
	public function test_existsByField_ObjectExistsInOneOfTheTables_ReturnTrue()
	{
		$subject = $this->subject(
			['a' => 1],
			['a' => 2, 'c' => 3]);
		
		$result = $subject->existsByField('a', 2);
		
		self::assertTrue($result);
	}
	
	public function test_existsByField_ObjectExistsInOneOfTheTablesButItsNotMatchinRule_ReturnFalse()
	{
		$subject = $this->subject(
			['a' => 1],
			[]);
		
		$result = $subject->existsByField('b', 'DummyB');
		
		self::assertFalse($result);
	}
	
	
	public function test_existsByFields_ObjectNotInTables_ReturnFalse()
	{
		$subject = $this->subject();
		
		$result = $subject->existsByFields(['a' => [1, 2]]);
		
		self::assertFalse($result);
	}
	
	public function test_existsByFields_ObjectExistsInOneOfTheTables_ReturnTrue()
	{
		$subject = $this->subject(
			['a' => 1],
			['a' => 2, 'c' => 3]);
		
		$result = $subject->existsByFields(['a' => 2]);
		
		self::assertTrue($result);
	}
	
	public function test_existsByFields_ObjectInTableNotMatchingRules_ReturnFalse()
	{
		$subject = $this->subject(
			['a' => 1],
			['a' => 2, 'c' => 3]);
		
		$result = $subject->existsByFields(['a' => 2, 'b' => 'DummyA']);
		
		self::assertFalse($result);
	}
	
	
	public function test_selectObjectByField_ObjectNotFound_ReturnNull()
	{
		$subject = $this->subject();
		
		$result = $subject->selectObjectByField('a', 1);
		
		self::assertNull($result);
	}
	
	public function test_selectObjectByField_ObjectExistsInOneOfTheTables_ObjectSelected()
	{
		$subject = $this->subject(['a' => 1]);
		
		$result = $subject->selectObjectByField('a', 1);
		
		self::assertInstanceOf(DummyObject::class, $result);
		self::assertEquals(1, $result->a);
	}

	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_selectObjectByField_ObjectExistsInNumberOfTables_ExceptionThrown()
	{
		$subject = $this->subject(['a' => 1], ['a' => 1, 'c' => 2]);
		
		$subject->selectObjectByField('a', 1);
	}
	
	public function test_selectObjectByField_ObjectExistsInTableNotMatchingRules_ReturnNull()
	{
		$subject = $this->subject(['a' => 1]);
		
		$result = $subject->selectObjectByField('b', 'DummyB');
		
		self::assertNull($result);
	}
	
	
	public function test_selectObjectByFields_ObjectNotFound_ReturnNull()
	{
		$subject = $this->subject();
		
		$result = $subject->selectObjectByFields(['a' => 1]);
		
		self::assertNull($result);
	}
	
	public function test_selectObjectByFields_ObjectExistsInOneOfTheTables_ObjectSelected()
	{
		$subject = $this->subject(['a' => 1]);
		
		$result = $subject->selectObjectByFields(['a' => 1]);
		
		self::assertInstanceOf(DummyObject::class, $result);
		self::assertEquals(1, $result->a);
	}

	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_selectObjectByFields_ObjectExistsInNumberOfTables_ExceptionThrown()
	{
		$subject = $this->subject(['a' => 1], ['a' => 1, 'c' => 2]);
		
		$subject->selectObjectByFields(['a' => 1]);
	}
	
	public function test_selectObjectByFields_ObjectExistsInTableNotMatchingRules_ReturnNull()
	{
		$subject = $this->subject(['a' => 1]);
		
		$result = $subject->selectObjectByFields(['a' => 1, 'b' => 'DummyB']);
		
		self::assertNull($result);
	}
	
	
	public function test_selectFirstObjectByField_ObjectNotFound_ReturnNull()
	{
		$subject = $this->subject();
		
		$result = $subject->selectFirstObjectByField('a', 1);
		
		self::assertNull($result);
	}
	
	public function test_selectFirstObjectByField_ObjectExistsInOneOfTheTables_ObjectSelected()
	{
		$subject = $this->subject(['a' => 1]);
		
		$result = $subject->selectFirstObjectByField('a', 1);
		
		self::assertInstanceOf(DummyObject::class, $result);
		self::assertEquals(1, $result->a);
	}
	
	public function test_selectFirstObjectByField_ObjectExistsInNumberOfTables_FirstFoundObjectReturned()
	{
		$subject = $this->subject(['a' => 1], ['a' => 1, 'c' => 2]);
		
		$result = $subject->selectFirstObjectByField('a', 1);
		
		self::assertInstanceOf(DummyObject::class, $result);
		self::assertEquals(1, $result->a);
	}
	
	public function test_selectFirstObjectByField_ObjectExistsInTableNotMatchingRules()
	{
		$subject = $this->subject(['a' => 1], ['a' => 1, 'c' => 2]);
		
		$result = $subject->selectFirstObjectByField('a', 1);
		
		self::assertInstanceOf(DummyObject::class, $result);
	}
	
	
	public function test_selectFirstObjectByFields_ObjectNotFound_ReturnNull()
	{
		$subject = $this->subject();
		
		$result = $subject->selectFirstObjectByFields(['a' => 1]);
		
		self::assertNull($result);
	}
	
	public function test_selectFirstObjectByFields_ObjectExistsInOneOfTheTables_ObjectSelected()
	{
		$subject = $this->subject(['a' => 1]);
		
		$result = $subject->selectFirstObjectByFields(['a' => 1]);
		
		self::assertInstanceOf(DummyObject::class, $result);
		self::assertEquals(1, $result->a);
	}
	
	public function test_selectFirstObjectByFields_ObjectExistsInNumberOfTables_OnlyOneObjectReturned()
	{
		$subject = $this->subject(['a' => 1], ['a' => 1, 'c' => 2]);
		
		$result = $subject->selectFirstObjectByFields(['a' => 1]);
		
		self::assertInstanceOf(DummyObject::class, $result);
		self::assertEquals(1, $result->a);
	}
	
	public function test_selectFirstObjectByFields_ObjectExistsInTableNotMatchingRules_ReturnNull()
	{
		$subject = $this->subject(['a' => 1], ['a' => 1, 'c' => 2]);
		
		$result = $subject->selectFirstObjectByFields(['a' => 1, 'b' => 'DummyB']);
		
		self::assertInstanceOf(DummyObjectB::class, $result);
	}
	
	
	public function test_selectObjectsByFields_ObjectNotFound_ReturnEmptyArray()
	{
		$subject = $this->subject();
		
		$result = $subject->selectObjectsByFields(['a' => 1]);
		
		self::assertEquals([], $result);
	}
	
	public function test_selectObjectsByFields_ObjectInOneOfTheTables_ObjectsReturned()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]]);
		
		$result = $subject->selectObjectsByFields(['a' => [1, 2]]);
		
		self::assertCount(2, $result);
		self::assertInstanceOf(DummyObject::class, $result[0]);
		self::assertInstanceOf(DummyObject::class, $result[1]);
		self::assertEquals(1, $result[0]->a);
		self::assertEquals(2, $result[1]->a);
	}
	
	public function test_selectObjectsByFields_ObjectInAllTables_ObjectsReturned()
	{
		$subject = $this->subject([['a' => 1]], [['a' => 2, 'c' => 3]]);
		
		$result = $subject->selectObjectsByFields(['a' => [1, 2]]);
		
		self::assertCount(2, $result);
		self::assertInstanceOf(DummyObject::class, $result[0]);
		self::assertInstanceOf(DummyObjectB::class, $result[1]);
		self::assertEquals(1, $result[0]->a);
		self::assertEquals(2, $result[1]->a);
	}
	
	public function test_selectObjectsByFields_ObjectInOneOfTheTablesThatDoesNotMatchRules_OnlyMatchingObjectsReturned()
	{
		$subject = $this->subject([['a' => 1]], [['a' => 2, 'c' => 3]]);
		
		$result = $subject->selectObjectsByFields(['a' => [1, 2], 'b' => 'DummyA']);
		
		self::assertCount(1, $result);
		self::assertInstanceOf(DummyObject::class, $result[0]);
		self::assertEquals(1, $result[0]->a);
	}
	
	public function test_selectObjectsByFields_LimitReachedWhenQueriedFromOneOfTheTables()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]], [['a' => 1, 'c' => 3], ['a' => 2, 'c' => 3]]);
		
		$result = $subject->selectObjectsByFields(['a' => [1, 2]], 3);
		
		self::assertCount(3, $result);
	}


	/**
	 * @expectedException \Squid\Exceptions\SquidDevelopmentException
	 */
	public function test_selectObjects_OrderByPassed_ExceptionThrown()
	{
		$subject = $this->subject();
		$subject->selectObjects(['a']);
	}
	
	public function test_selectObjects_AllTablesEmpty_ReturnEmptyArray()
	{
		$subject = $this->subject();
		self::assertEquals([], $subject->selectObjects());
	}
	
	public function test_selectObjects_AllObjectReturned()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]], [['a' => 3, 'c' => 3]]);
		
		$result = $subject->selectObjects();
		
		self::assertCount(3, $result);
		self::assertInstanceOf(DummyObject::class, $result[0]);
		self::assertInstanceOf(DummyObject::class, $result[1]);
		self::assertInstanceOf(DummyObjectB::class, $result[2]);
		self::assertEquals(1, $result[0]->a);
		self::assertEquals(2, $result[1]->a);
		self::assertEquals(3, $result[2]->a);
	}
	
	
	public function test_deleteByField_NothingDeleted_Return0()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]], [['a' => 3, 'c' => 3], ['a' => 4, 'c' => 5]]);
		
		$result = $subject->deleteByField('a', 5);
		
		self::assertEquals(0, $result);
	}
	
	public function test_deleteByField_ObjectExistsInAllTables_DeletedFromBothTables()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]], [['a' => 3, 'c' => 3], ['a' => 4, 'c' => 5]]);
		
		$subject->deleteByField('a', [1, 4]);
		
		self::assertRowCount(1, $this->tableA);
		self::assertRowExists($this->tableA, ['a' => 2]);
		self::assertRowCount(1, $this->tableB);
		self::assertRowExists($this->tableB, ['a' => 3]);
	}
	
	public function test_deleteByField_ObjectExistsInAllTables_ReturnTotalCount()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]], [['a' => 3, 'c' => 3], ['a' => 4, 'c' => 5]]);
		
		$result = $subject->deleteByField('a', [1, 4]);
		
		self::assertEquals(2, $result);
	}
	
	public function test_deleteByField_MatchingOnlyOneSelectGroup_OnlyMatchingTableIsUsed()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]], [['a' => 3, 'c' => 3], ['a' => 4, 'c' => 5]]);
		
		$result = $subject->deleteByField('b', 'DummyA');
		
		self::assertEquals(2, $result);
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(2, $this->tableB);
	}
	
	public function test_deleteByField_LimitUsedAndReachedInOneOfTheTabls()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]], [['a' => 1, 'c' => 3], ['a' => 2, 'c' => 5]]);
		
		$result = $subject->deleteByField('a', [1, 2], 3);
		
		self::assertEquals(3, $result);
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(1, $this->tableB);
	}
	
	
	public function test_deleteByFields_NothingDeleted_Return0()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]], [['a' => 3, 'c' => 3], ['a' => 4, 'c' => 5]]);
		
		$result = $subject->deleteByFields(['a' => 5]);
		
		self::assertEquals(0, $result);
	}
	
	public function test_deleteByFields_ObjectExistsInAllTables_DeletedFromBothTables()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]], [['a' => 3, 'c' => 3], ['a' => 4, 'c' => 5]]);
		
		$subject->deleteByFields(['a' => [1, 4]]);
		
		self::assertRowCount(1, $this->tableA);
		self::assertRowExists($this->tableA, ['a' => 2]);
		self::assertRowCount(1, $this->tableB);
		self::assertRowExists($this->tableB, ['a' => 3]);
	}
	
	public function test_deleteByFields_ObjectExistsInAllTables_ReturnTotalCount()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]], [['a' => 3, 'c' => 3], ['a' => 4, 'c' => 5]]);
		
		$result = $subject->deleteByFields(['a' => [1, 4]]);
		
		self::assertEquals(2, $result);
	}
	
	public function test_deleteByFields_MatchingOnlyOneSelectGroup_OnlyMatchingTableIsUsed()
	{
		$subject = $this->subject([['a' => 1]], [['a' => 1, 'c' => 3]]);
		
		$result = $subject->deleteByFields(['a' => 1, 'b' => 'DummyA']);
		
		self::assertEquals(1, $result);
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(1, $this->tableB);
	}
	
	public function test_deleteByFields_LimitUsedAndReachedInOneOfTheTabls()
	{
		$subject = $this->subject([['a' => 1], ['a' => 2]], [['a' => 1, 'c' => 3], ['a' => 2, 'c' => 5]]);
		
		$result = $subject->deleteByFields(['a' => [1, 2]], 3);
		
		self::assertEquals(3, $result);
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(1, $this->tableB);
	}
	
	
	public function test_insertObjects_SingleObjectPassed_Return1()
	{
		$subject = $this->subject();
		
		$result = $subject->insertObjects($this->dummyA(1));
		
		self::assertEquals(1, $result);
	}
	
	public function test_insertObjects_SingleObjectPassed_ObjectInsertedIntoTheCorrectTable()
	{
		$subject = $this->subject();
		$subject->insertObjects($this->dummyA(1));
		
		self::assertRowCount(1, $this->tableA);
		self::assertRowCount(0, $this->tableB);
		
		$subject = $this->subject();
		$subject->insertObjects($this->dummyB(1));
		
		self::assertRowCount(0, $this->tableA);
		self::assertRowCount(1, $this->tableB);
	}
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_insertObjects_InsertSingleObjectThatAlreadyExists_ThrowsException()
	{
		$subject = $this->subject(['a' => 1]);
		$subject->insertObjects($this->dummyA(1));
	}
	
	public function test_insertObjects_InsertSingleObjectThatAlreadyExistsWithIgnoreFlag_Return0()
	{
		$subject = $this->subject(['a' => 1]);
		
		$result = $subject->insertObjects($this->dummyA(1), true);
		
		self::assertEquals(0, $result);
	}
	
	public function test_insertObjects_ArrayOfObjectsPassed_ObjectsInsertedIntoCorrectTables()
	{
		$subject = $this->subject();
		
		$subject->insertObjects([$this->dummyA(1), $this->dummyA(2), $this->dummyB(3)]);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
	}
	
	public function test_insertObjects_ArrayOfObjectsPassed_CorrectCountReturned()
	{
		$subject = $this->subject();
		
		$result = $subject->insertObjects([$this->dummyA(1), $this->dummyA(2), $this->dummyB(3)]);
		
		self::assertEquals(3, $result);
	}
	
	public function test_insertObjects_SomeObjectsAreDuplicatedWithIngoreFlagOn_DuplicatesIgnored()
	{
		$subject = $this->subject(['a' => 2]);
		
		$result = $subject->insertObjects([$this->dummyA(1), $this->dummyA(2), $this->dummyB(3)], true);
		
		self::assertEquals(2, $result);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
	}
	
	
	public function test_updateObject_ObjectDoesNotExists_Return0()
	{
		$subject = $this->subject();
		
		$result = $subject->updateObject($this->dummyA(1), ['b']);
		
		self::assertEquals(0, $result);
	}
	
	public function test_updateObject_ObjectExistsInOneOfTheTables_Return1()
	{
		$subject = $this->subject(['a' => 1]);
		
		$result = $subject->updateObject($this->dummyA(2), ['b']);
		
		self::assertEquals(1, $result);
	}
	
	public function test_updateObject_ObjectExistsInOneOfTheTables_ObjectUpdated()
	{
		$subject = $this->subject(['a' => 1]);
		
		$subject->updateObject($this->dummyA(2), ['b']);
		
		self::assertRowCount(1, $this->tableA);
		self::assertRowExists($this->tableA, ['a' => 2]);
	}
	
	
	public function test_upsertObjectsByKeys_ObjectDoesNotExists_Return1()
	{
		$subject = $this->subject();
		
		$result = $subject->upsertObjectsByKeys($this->dummyA(1), ['a']);
		
		self::assertEquals(1, $result);
	}
	
	public function test_upsertObjectsByKeys_ObjectDoesNotExists_ObjectInserted()
	{
		$subject = $this->subject();
		
		$subject->upsertObjectsByKeys($this->dummyA(1), ['a']);
		
		self::assertRowCount(1, $this->tableA);
		self::assertRowExists($this->tableA, ['a' => 1]);
		self::assertRowCount(0, $this->tableB);
	}
	
	public function test_upsertObjectsByKeys_ObjectExistsInOneOfTheTables_Return2()
	{
		$subject = $this->subject([], ['a' => 1, 'c' => 4]);
		
		$result = $subject->upsertObjectsByKeys($this->dummyB(1, 5), ['a']);
		
		self::assertEquals(2, $result);
	}
	
	public function test_upsertObjectsByKeys_ObjectExistsInOneOfTheTables_ObjectUpdated()
	{
		$subject = $this->subject([], ['a' => 1, 'c' => 4]);
		
		$subject->upsertObjectsByKeys($this->dummyB(1, 5), ['a']);
		
		self::assertRowCount(1, $this->tableB);
		self::assertRowExists($this->tableB, ['a' => 1, 'c' => 5]);
		
		self::assertRowCount(0, $this->tableA);
	}
	
	public function test_upsertObjectsByKeys_ArrayOfObjectsPassed()
	{
		$subject = $this->subject(['a' => 1], [['a' => 2, 'c' => 3], ['a' => 3, 'c' => 5]]);
		
		$res = $subject->upsertObjectsByKeys([$this->dummyA(2), $this->dummyB(2, 4)], ['a']);
		
		// 1 inserted, 1 modified
		self::assertEquals(3, $res);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowExists($this->tableA, ['a' => 1]);
		self::assertRowExists($this->tableA, ['a' => 2]);
		
		self::assertRowCount(2, $this->tableB);
		self::assertRowExists($this->tableB, ['a' => 2, 'c' => 4]);
		self::assertRowExists($this->tableB, ['a' => 3]);
	}
	
	
	public function test_upsertObjectsForValues_ObjectDoesNotExists_Return1()
	{
		$subject = $this->subject();
		
		$result = $subject->upsertObjectsForValues($this->dummyA(1), ['b']);
		
		self::assertEquals(1, $result);
	}
	
	public function test_upsertObjectsForValues_ObjectDoesNotExists_ObjectInserted()
	{
		$subject = $this->subject();
		
		$subject->upsertObjectsForValues($this->dummyA(1), ['b']);
		
		self::assertRowCount(1, $this->tableA);
		self::assertRowExists($this->tableA, ['a' => 1]);
		self::assertRowCount(0, $this->tableB);
	}
	
	public function test_upsertObjectsForValues_ObjectExistsInOneOfTheTables_Return2()
	{
		$subject = $this->subject([], ['a' => 1, 'c' => 4]);
		
		$result = $subject->upsertObjectsForValues($this->dummyB(1, 5), ['b', 'c']);
		
		self::assertEquals(2, $result);
	}
	
	public function test_upsertObjectsForValues_ObjectExistsInOneOfTheTables_ObjectUpdated()
	{
		$subject = $this->subject([], ['a' => 1, 'c' => 4]);
		
		$subject->upsertObjectsForValues($this->dummyB(1, 5), ['b', 'c']);
		
		self::assertRowCount(1, $this->tableB);
		self::assertRowExists($this->tableB, ['a' => 1, 'c' => 5]);
		
		self::assertRowCount(0, $this->tableA);
	}
	
	public function test_upsertObjectsForValues_ArrayOfObjectsPassed()
	{
		$subject = $this->subject(['a' => 1], [['a' => 2, 'c' => 3], ['a' => 3, 'c' => 5]]);
		
		$res = $subject->upsertObjectsForValues([$this->dummyA(2), $this->dummyB(2, 4)], ['b']);
		
		// 1 inserted, 0 modified
		self::assertEquals(1, $res);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowExists($this->tableA, ['a' => 1]);
		self::assertRowExists($this->tableA, ['a' => 2]);
		
		self::assertRowCount(2, $this->tableB);
		self::assertRowExists($this->tableB, ['a' => 2, 'c' => 3]);
		self::assertRowExists($this->tableB, ['a' => 3, 'c' => 5]);
	}
}