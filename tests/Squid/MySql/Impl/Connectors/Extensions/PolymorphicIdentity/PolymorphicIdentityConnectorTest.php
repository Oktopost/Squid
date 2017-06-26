<?php
namespace Squid\MySql\Impl\Connectors\Extensions\PolymorphicIdentity;


use lib\DataSet;
use lib\TDBAssert;
use Objection\LiteSetup;
use Objection\LiteObject;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Connectors\Object\CRUD\IIdentifiedObjectConnector;
use Squid\MySql\Impl\Connectors\Object\SimpleObjectConnector;


class PolymorphicIdentityConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $doSetupKeys = false;
	
	// Table A has elements with id < 100
	private $tableA;
	
	// Table B has elements with id > 100 and < 10000
	private $tableB;
	
	
	private function insertAObjects($data)
	{
		if ($data && !is_array($data[0]))
			$data = [$data];
		
		return DataSet::table(['a', 'b'], $data);
	}
	
	private function insertBObjects($data)
	{
		if ($data && !is_array($data[0]))
			$data = [$data];
		
		return DataSet::table(['a', 'b', 'c'], $data);
	}
	
	private function subject(array $dataA = [], array $dataB = [])
	{
		$this->tableA = $this->insertAObjects($dataA);
		$this->tableB = $this->insertBObjects($dataB);
		$config = new PolymorphicIdentityConnectorTestConfig($this->tableA, $this->tableB);
		
		if ($this->doSetupKeys)
		{
			DataSet::connector()->direct("ALTER TABLE {$this->tableA} ADD PRIMARY KEY (a)")->executeDml();
			DataSet::connector()->direct("ALTER TABLE {$this->tableB} ADD PRIMARY KEY (a)")->executeDml();
		}
		
		return new PolymorphicIdentityConnector($config);
	}
	
	private function setKeys()
	{
		$this->doSetupKeys = true;
	}
	
	
	public static function setUpBeforeClass()
	{
		DataSet::clearDB();
	}
	
	protected function setUp()
	{
		$this->doSetupKeys = false;
	}


	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_load_InvalidID_ErrorThrown()
	{
		$this->subject()->load(10001);
	}

	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_load_OneOfTheIDsIsInvalid_ErrorThrown()
	{
		$this->subject()->load([1, 2, 10001, 3]);
	}
	
	public function test_load_ObjectNotFound_ReturnNull()
	{
		self::assertNull($this->subject()->load(1));
	}
	
	public function test_load_ObjectInFirstConnector_ObjectLoaded()
	{
		$subject = $this->subject([1, 2]);
		$result = $subject->load(1);
		
		self::assertNotNull($result);
		self::assertEquals(['a' => 1, 'b' => 2], $result->toArray());
	}
	
	public function test_load_ObjectInSecondConnector_ObjectLoaded()
	{
		$subject = $this->subject([], [100, 2, 3]);
		$result = $subject->load(100);
		
		self::assertNotNull($result);
		self::assertEquals(['a' => 100, 'b' => 2, 'c' => 3], $result->toArray());
	}
	
	public function test_load_MoreThenOneObjectExists_OnlyRequestedObjectLoaded()
	{
		$subject = $this->subject([[1, 2], [3, 4], [5, 6]], []);
		$result = $subject->load(3);
		
		self::assertNotNull($result);
		self::assertEquals(['a' => 3, 'b' => 4], $result->toArray());
	}
	
	public function test_load_RequestArrayOfIds_ArrayReturned()
	{
		$subject = $this->subject();
		$result = $subject->load([1]);
		
		self::assertTrue(is_array($result));
	}
	
	public function test_load_RequestArrayOfIds_ObjectNotFound_EmptyArrayReturned()
	{
		$subject = $this->subject();
		$result = $subject->load([1]);
		
		self::assertEmpty($result);
	}
	
	public function test_load_RequestArrayOfIds_ObjectFound_ObjectReturnedInArray()
	{
		$subject = $this->subject([1, 2]);
		$result = $subject->load([1, 3]);
		
		self::assertCount(1, $result);
		
		/** @noinspection PhpUndefinedMethodInspection */
		self::assertEquals(['a' => 1, 'b' => 2], ($result[0])->toArray());
	}
	
	public function test_load_RequestArrayOfIds_RequestObjectsFromDifferentConnectors()
	{
		$subject = $this->subject([1, 2], [101, 2, 3]);
		$result = $subject->load([1, 101]);
		
		self::assertCount(2, $result);
		
		if ($result[0]->a != 1)
		{
			$temp = $result[1];
			$result[1] = $result[0];
			$result[0] = $temp;
		}
		
		/** @noinspection PhpUndefinedMethodInspection */
		self::assertEquals(['a' => 1, 'b' => 2], ($result[0])->toArray());
		
		/** @noinspection PhpUndefinedMethodInspection */
		self::assertEquals(['a' => 101, 'b' => 2, 'c' => 3], ($result[1])->toArray());
	}
	
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_deleteById_InvalidID_ErrorThrown()
	{
		$this->subject()->deleteById(10001);
	}

	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_deleteById_OneOfTheIDsIsInvalid_ErrorThrown()
	{
		$this->subject()->deleteById([1, 2, 10001, 3]);
	}
	
	public function test_deleteById_NoRowsModified_Return0()
	{
		self::assertEquals(0, $this->subject()->deleteById(1));
	}
	
	public function test_deleteById_RecordsDeletedFromOneTable_ReturnCount()
	{
		$subject = $this->subject([[1, 2], [3, 4]]);
		self::assertEquals(2, $subject->deleteById([1, 3]));
	}
	
	public function test_deleteById_RecordsDeletedFromManyTables_ReturnCount()
	{
		$subject = $this->subject([[1, 2], [3, 4]], [[102, 2, 3]]);
		self::assertEquals(3, $subject->deleteById([1, 3, 102]));
	}
	
	public function test_deleteById_MoreRecordsExist_OnlyRequestedRecordDeleted()
	{
		$subject = $this->subject([[1, 2], [3, 4]], [[102, 2, 3]]);
		$subject->deleteById([3]);
		
		self::assertRowExists($this->tableA, 'a', 1);
		self::assertRowExists($this->tableB, 'a', 102);
	}
	
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_delete_InvalidObject_ErrorThrown()
	{
		$this->subject()->delete($this);
	}
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_delete_OneOfTheObjectsIsInvalid()
	{
		$this->subject()->delete([new PolyHelper_A(), $this, new PolyHelper_B()]);
	}
	
	
	public function test_delete_ObjectNotFound_Return0()
	{
		$this->assertEquals(0, $this->subject()->delete((new PolyHelper_A())->fromArray(['a' => 1])));
	}
	
	public function test_delete_ArrayOfObjectsNotFound_Return0()
	{
		$this->assertEquals(0, $this->subject()->delete(
			[
				(new PolyHelper_A())->fromArray(['a' => 1]),
				(new PolyHelper_B())->fromArray(['a' => 100])
			]));
	}
	
	public function test_delete_RecordsDeletedFromOneTable_ReturnCount()
	{
		$subject = $this->subject([[1, 2], [3, 4]]);
		
		self::assertEquals(2, $subject->delete([
			(new PolyHelper_A())->fromArray(['a' => 1]),
			(new PolyHelper_A())->fromArray(['a' => 3])
		]));
	}
	
	public function test_delete_RecordsDeletedFromManyTables_ReturnCount()
	{
		$subject = $this->subject([[1, 2], [3, 4]], [[102, 2, 3]]);
		
		self::assertEquals(2, $subject->delete([
			(new PolyHelper_A())->fromArray(['a' => 1]),
			(new PolyHelper_B())->fromArray(['a' => 102])
		]));
	}
	
	public function test_delete_MoreRecordsExist_OnlyRequestedRecordDeleted()
	{
		$subject = $this->subject([[1, 2], [3, 4]], [[102, 2, 3]]);
		
		$subject->delete([
			(new PolyHelper_A())->fromArray(['a' => 3])
		]);
		
		self::assertRowExists($this->tableA, 'a', 1);
		self::assertRowExists($this->tableB, 'a', 102);
	}
	
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_update_InvalidObject_ErrorThrown()
	{
		$this->subject()->update($this);
	}
	
	public function test_update_ObjectNotFound_Return0()
	{
		$this->assertEquals(0, $this->subject()->update((new PolyHelper_A())->fromArray(['a' => 1])));
	}
	
	public function test_update_ObjectUpdated_Return1()
	{
		$subject = $this->subject([[1, 2]]);
		
		self::assertEquals(1, $subject->update(
			(new PolyHelper_A())->fromArray(['a' => 1, 'b' => 3])
		));
	}
	
	public function test_update_ObjectUpdated_DataChanged()
	{
		$subject = $this->subject([[1, 2]]);
		
		$subject->update(
			(new PolyHelper_A())->fromArray(['a' => 1, 'b' => 3])
		);
		
		self::assertRowExists($this->tableA, 'b', 3);
		self::assertRowCount(1, $this->tableA);
	}
	
	public function test_update_OtherObjectsExist_ObjectUpdatedInCorrectTable()
	{
		$subject = $this->subject([[1, 2], [3, 4]], [[101, 1, 2], [102, 3, 4]]);
		
		$subject->update(
			(new PolyHelper_B())->fromArray(['a' => 102, 'b' => 5, 'c' => 6])
		);
		
		self::assertRowExists($this->tableB, ['a' => 101, 'b' => 1, 'c' => 2]);
		self::assertRowExists($this->tableB, ['a' => 102, 'b' => 5, 'c' => 6]);
		
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 2]);
		self::assertRowExists($this->tableA, ['a' => 3, 'b' => 4]);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(2, $this->tableB);
	}
	
	public function test_update_OtherObjectsExist_OnlyOneOBjectUpdated_Return1()
	{
		$subject = $this->subject([[1, 2], [3, 4]], [[101, 1, 2], [102, 3, 4]]);
		
		self::assertEquals(1, 
			$subject->update(
				(new PolyHelper_B())->fromArray(['a' => 102, 'b' => 5, 'c' => 6])
			)
		);
	}
	
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_upsert_InvalidObject_ErrorThrown()
	{
		$this->setKeys();
		$this->subject()->upsert($this);
	}
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_upsert_OneOfTheObjectsIsInvalid()
	{
		$this->setKeys();
		$this->subject()->upsert([new PolyHelper_A(), $this, new PolyHelper_B()]);
	}
	
	public function test_upsert_ObjectNotFound_ObjectInserted()
	{
		$this->setKeys();
		$this->subject()->upsert((new PolyHelper_A())->fromArray(['a' => 1, 'b' => 2]));
		
		self::assertRowCount(1, $this->tableA);
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 2]);
	}
	
	public function test_upsert_ObjectNotFound_Return1()
	{
		$this->setKeys();
		$this->assertEquals(1, $this->subject()->upsert((new PolyHelper_A())->fromArray(['a' => 1])));
	}
	
	public function test_upsert_ObjectNotModified_Return0()
	{
		$this->setKeys();
		$subject = $this->subject([[1, 2]]);
		
		self::assertEquals(
			0, 
			$subject->upsert(
				(new PolyHelper_A())->fromArray(['a' => 1, 'b' => 2])
			)
		);
	}
	
	public function test_upsert_ObjectUpdated_Return2()
	{
		$this->setKeys();
		$subject = $this->subject([[1, 2]]);
		
		self::assertEquals(
			2, 
			$subject->upsert(
				(new PolyHelper_A())->fromArray(['a' => 1, 'b' => 3])
			)
		);
	}
	
	public function test_upsert_ObjectUpdated_DataChanged()
	{
		$this->setKeys();
		$subject = $this->subject([[1, 2]]);
		
		$subject->upsert(
			(new PolyHelper_A())->fromArray(['a' => 1, 'b' => 3])
		);
		
		self::assertRowExists($this->tableA, 'b', 3);
		self::assertRowCount(1, $this->tableA);
	}
	
	public function test_upsert_OtherObjectsExist_ObjectUpdatedInCorrectTable()
	{
		$this->setKeys();
		$subject = $this->subject([[1, 2], [3, 4]], [[101, 1, 2], [102, 3, 4]]);
		
		$subject->upsert(
			(new PolyHelper_B())->fromArray(['a' => 102, 'b' => 5, 'c' => 6])
		);
		
		self::assertRowExists($this->tableB, ['a' => 101, 'b' => 1, 'c' => 2]);
		self::assertRowExists($this->tableB, ['a' => 102, 'b' => 5, 'c' => 6]);
		
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 2]);
		self::assertRowExists($this->tableA, ['a' => 3, 'b' => 4]);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(2, $this->tableB);
	}
	
	public function test_upsert_OtherObjectsExist_OnlyTargetOBjectUpdated_Return1()
	{
		$this->setKeys();
		$subject = $this->subject([[1, 2], [3, 4]], [[101, 1, 2], [102, 3, 4]]);
		
		self::assertEquals(
			2, 
			$subject->upsert(
				(new PolyHelper_B())->fromArray(['a' => 102, 'b' => 5, 'c' => 6])
			)
		);
	}
	
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_insert_InvalidObject_ErrorThrown()
	{
		$this->subject()->insert($this);
	}
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_insert_OneOfTheObjectsIsInvalid()
	{
		$this->subject()->insert([new PolyHelper_A(), $this, new PolyHelper_B()]);
	}
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_insert_KeyAlreadyExists()
	{
		$this->setKeys();
		$subject = $this->subject([[1, 2]]);
		
		$subject->insert([(new PolyHelper_A())->fromArray(['a' => 1])]);
	}
	
	
	public function test_insert_ObjectNotFound_ObjectInserted()
	{
		$this->setKeys();
		$this->subject()->insert((new PolyHelper_A())->fromArray(['a' => 1, 'b' => 2]));
		
		self::assertRowCount(1, $this->tableA);
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 2]);
	}
	
	public function test_insert_ObjectNotFound_Return1()
	{
		$this->setKeys();
		$this->assertEquals(1, $this->subject()->insert((new PolyHelper_A())->fromArray(['a' => 1])));
	}
	
	public function test_insert_IgnoreErrors_ObjectNotInserted_Return0()
	{
		$this->setKeys();
		$subject = $this->subject([[1, 2]]);
		
		self::assertEquals(
			0, 
			$subject->insert(
				(new PolyHelper_A())->fromArray(['a' => 1, 'b' => 2]),
				true
			)
		);
	}
	
	public function test_insert_IgnoreErrors_SomeObjectNotInserted_Return0()
	{
		$this->setKeys();
		$subject = $this->subject([[1, 2], [3, 4]]);
		
		self::assertEquals(
			0, 
			$subject->insert(
				[
					(new PolyHelper_A())->fromArray(['a' => 1, 'b' => 2]),
					(new PolyHelper_A())->fromArray(['a' => 5, 'b' => 6]),
				],
				true
			)
		);
	}
	
	public function test_insert_InsertIntoMultipleTables_DataInserted()
	{
		$this->setKeys();
		$subject = $this->subject();
		
		$subject->insert(
			(new PolyHelper_A())->fromArray(['a' => 1, 'b' => 2]),
			(new PolyHelper_B())->fromArray(['a' => 101, 'b' => 3, 'c' => 4])
		);
		
		self::assertRowExists($this->tableB, ['a' => 101, 'b' => 3, 'c' => 4]);
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 2]);
		
		self::assertRowCount(1, $this->tableA);
		self::assertRowCount(1, $this->tableB);
	}
	
	public function test_insert_OtherObjectsExist_OnlyNewObjectsInserted()
	{
		$this->setKeys();
		$subject = $this->subject([[1, 2]], [[101, 1, 2]]);
		
		$subject->insert(
			[
				(new PolyHelper_A())->fromArray(['a' => 1, 'b' => 5]),
				(new PolyHelper_A())->fromArray(['a' => 3, 'b' => 4]),
				(new PolyHelper_B())->fromArray(['a' => 101, 'b' => 3, 'c' => 4]),
				(new PolyHelper_B())->fromArray(['a' => 102, 'b' => 3, 'c' => 4])
			],
			true
		);
		
		self::assertRowExists($this->tableB, ['a' => 101, 'b' => 1, 'c' => 2]);
		self::assertRowExists($this->tableB, ['a' => 102, 'b' => 3, 'c' => 4]);
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 2]);
		self::assertRowExists($this->tableA, ['a' => 3, 'b' => 4]);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(2, $this->tableB);
	}
	
	public function test_insert_OtherObjectsExist_CorrectCountReturned()
	{
		$this->setKeys();
		$subject = $this->subject([[1, 2]], [[101, 1, 2]]);
		
		$count = $subject->insert(
			[
				(new PolyHelper_A())->fromArray(['a' => 1, 'b' => 5]),
				(new PolyHelper_A())->fromArray(['a' => 3, 'b' => 4]),
				(new PolyHelper_B())->fromArray(['a' => 101, 'b' => 3, 'c' => 4]),
				(new PolyHelper_B())->fromArray(['a' => 102, 'b' => 3, 'c' => 4])
			],
			true
		);
		
		self::assertEquals(2, $count);
	}
}


class PolymorphicIdentityConnectorTestConfig extends AbstractPolymorphicIdentityConfig
{
	private $tableA;
	private $tableB;
	
	
	private $connA = null;
	private $connB = null;
	
	
	private function create($table, $class)
	{
		$conn = new SimpleObjectConnector();
		return $conn->setConnector(DataSet::connector())
			->setObjectMap($class)
			->setTable($table)
			->setIDProperty('a');
	}
	
	
	public function __construct($tableA, $tableB)
	{
		$this->tableA = $tableA;
		$this->tableB = $tableB;
	}


	public function getConnector(string $type): IIdentifiedObjectConnector
	{
		if ($type === 'a')
		{
			if ($this->connA === null)
				$this->connA = $this->create($this->tableA, PolyHelper_A::class);
				
			return $this->connA;
		}
		else if ($type === 'b')
		{
			if ($this->connB === null)
				$this->connB = $this->create($this->tableB, PolyHelper_B::class);
				
			return $this->connB;
		}
		
		throw new \Exception('FAIL!');
	}

	public function getTypeByIdentity($id): ?string
	{
		return ($id < 100 ? 'a' : ($id < 10000 ? 'b' : null)); 
	}

	public function getTypeByObject($object): ?string
	{
		return ($object instanceof PolyHelper_A ? 
			'a' : 
			($object instanceof PolyHelper_B ? 'b' : null));
	}
}



class PolyHelper_A extends LiteObject
{
	protected function _setup()
	{
		return [
			'a'	=> LiteSetup::createInt(null),
			'b'	=> LiteSetup::createInt(0)
		];
	}
}

class PolyHelper_B extends LiteObject
{
	protected function _setup()
	{
		return [
			'a'	=> LiteSetup::createInt(null),
			'b'	=> LiteSetup::createInt(0),
			'c'	=> LiteSetup::createInt(0)
		];
	}
}