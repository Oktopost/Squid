<?php
namespace Squid\MySql\Impl\Connectors\Objects;


use lib\DataSet;
use lib\TDBAssert;

use Objection\LiteSetup;
use Objection\LiteObject;

use PHPUnit\Framework\TestCase;
use Squid\MySql\Connectors\Objects\Generic\IGenericObjectConnector;
use Squid\MySql\Impl\Connectors\Objects\Generic\GenericObjectConnector;
use Squid\MySql\Impl\Connectors\Objects\IdentityConnector;


class IdentityConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $table;
	
	
	private function createObject($a, $b, $c): IdentityConnectorTestHelper
	{
		$result = new IdentityConnectorTestHelper();
		
		$result->a = $a;
		$result->b = $b;
		$result->c = $c;
		
		self::$LAST_ROW = $result->toArray();
		
		return $result;
	}
	
	private function newDifferentPropertiesObject($a, $b, $c): IdentityConnectorTestHelperDiffProperties
	{
		$result = new IdentityConnectorTestHelperDiffProperties();
		
		$result->A = $a;
		$result->B = $b;
		$result->C = $c;
		
		self::$LAST_ROW = $result->toArray();
		
		return $result;
	}
	
	private function subject(array $data = [], $properties = [], $class = IdentityConnectorTestHelper::class): IdentityConnector
	{
		$properties = $properties ?: ['a', 'b'];
		
		$this->table = DataSet::table(['a', 'b', 'c'], $data);
		
		DataSet::connector()
			->direct("ALTER TABLE {$this->table} ADD PRIMARY KEY (a, b)")
			->executeDml();
		
		$connector = new IdentityConnector();
		$connector->setConnector(DataSet::connector());
		$connector->setObjectMap($class);
		$connector->setTable($this->table);
		$connector->setPrimaryKeys($properties);
		
		return $connector;
	}
	
	private function subjectOverrideGeneric($generic): IdentityConnector
	{
		$connector = new OverridedIdentityConnectorTestHelper();
		$connector->setConnector(DataSet::connector());
		$connector->setPrimaryKeys(['a', 'b']);
		$connector->genericConnector = $generic;
		
		return $connector;
	}
	
	private function idSubject(array $data = []): IdentityConnector
	{
		$this->table = DataSet::table(['a', 'b', 'c'], $data);
		
		DataSet::connector()
			->direct("ALTER TABLE {$this->table} ADD PRIMARY KEY (a)")
			->executeDml();
		
		$connector = new IdentityConnector();
		$connector->setConnector(DataSet::connector());
		$connector->setObjectMap(IdentityConnectorTestHelper::class);
		$connector->setTable($this->table);
		$connector->setPrimaryKeys(['a']);
		
		return $connector;
	}
	
	
	public function test_setPrimaryKeys_ReturnSelf()
	{
		$connector = new IdentityConnector();
		self::assertEquals($connector, $connector->setPrimaryKeys('a'));
	}
	
	public function test_setPrimaryKeys_PassPropetiesWithDifferentFormat()
	{
		$subject = $this->subject(
			[$this->row(1, 2, 3), $this->row(2, 2, 3)],
			['A', 'B'], 
			IdentityConnectorTestHelperDiffProperties::class);
		
		$subject->delete($this->newDifferentPropertiesObject(1, 2, 3));
		
		self::assertRowCount(1, $this->table);
		self::assertRowExists($this->table, $this->row(2, 2, 3));
	}
	
	
	public function test_delete_ObjectNotFound_Return0()
	{
		$res = $this->subject()->delete($this->createObject(1, 2, 3));
		self::assertEquals(0, $res);
	}
	
	public function test_delete_OtherObjectsExistsInTheDB_OtherObjectsNotDeleted()
	{
		$this->subject($this->row(1, 2, 3))->delete($this->createObject(1, 3, 3));
		self::assertRowExists($this->table, $this->row(1, 2, 3));
	}
	
	public function test_delete_ObjectInDB_ObjectDeleted()
	{
		$this->subject($this->row(1, 2, 3))->delete($this->createObject(1, 2, 4));
		self:self::assertRowCount(0, $this->table);
	}
	
	public function test_delete_ObjectDeleted_Return1()
	{
		$res = $this->subject($this->row(1, 2, 3))->delete($this->createObject(1, 2, 4));
		self::assertEquals(1, $res);
	}
	
	public function test_delete_MultipleObjectsPassed_AllObjectsDeleted()
	{
		$this
			->subject(
				[
					$this->row(1, 2, 3),
					$this->row(4, 5, 6)
				]
			)
			->delete(
				[
					$this->createObject(1, 2, 4),
					$this->createObject(4, 5, 7)
				]);
		
		self::assertRowCount(0, $this->table);
	}
	
	public function test_delete_MultipleObjectsPassed_CorrectCountReturned()
	{
		$res = $this
			->subject(
				[
					$this->row(1, 2, 3),
					$this->row(4, 5, 6)
				]
			)
			->delete(
				[
					$this->createObject(1, 2, 4),
					$this->createObject(4, 5, 7)
				]);
		
		self::assertEquals(2, $res);
	}
	
	public function test_delete_ObjectHasSingleKey_AllRequestedObjectsDeleted()
	{
		$res = $this
			->idSubject(
				[
					$this->row(1, 2, 3),
					$this->row(4, 5, 6),
					$this->row(8, 5, 6)
				]
			)
			->delete(
				[
					$this->createObject(1, 10, 100),
					$this->createObject(4, 10, 100)
				]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->table);
		self::assertRowExists($this->table, $this->row(8, 5, 6));
	}
	
	
	public function test_update_Sanity()
	{
		$generic = $this->getMockBuilder(GenericObjectConnector::class)->getMock();
		$subject = $this->subjectOverrideGeneric($generic);
		$object = $this->createObject(1, 2, 5);
		
		$generic->expects($this->once())->method('updateObject')->with($object, ['a', 'b']);
		
		$subject->update($object);
	}
	
	public function test_upsert_Sanity()
	{
		$generic = $this->getMockBuilder(GenericObjectConnector::class)->getMock();
		$subject = $this->subjectOverrideGeneric($generic);
		$objects = [$this->createObject(1, 2, 5), $this->createObject(1, 2, 5)];
		
		$generic->expects($this->once())->method('upsertObjectsByKeys')->with($objects, ['a', 'b']);
		
		$subject->upsert($objects);
	}
	
	public function test_insert_Sanity()
	{
		$generic = $this->getMockBuilder(GenericObjectConnector::class)->getMock();
		$subject = $this->subjectOverrideGeneric($generic);
		$object1 = $this->createObject(1, 2, 5);
		$object2 = $this->createObject(1, 2, 5);
		
		$generic->expects($this->once())->method('insertObjects')->with([$object1, $object2], false);
		
		$subject->insert([$object1, $object2]);
	}
}


/**
 * @property string $a
 * @property string $b
 */
class IdentityConnectorTestHelper extends LiteObject
{
	protected function _setup()
	{
		return [
			'a' => LiteSetup::createInt(),
			'b' => LiteSetup::createInt(),
			'c' => LiteSetup::createInt()
		];
	}
}

class IdentityConnectorTestHelperDiffProperties extends LiteObject
{
	protected function _setup()
	{
		return [
			'A' => LiteSetup::createInt(),
			'B' => LiteSetup::createInt(),
			'C' => LiteSetup::createInt()
		];
	}
}


class OverridedIdentityConnectorTestHelper extends IdentityConnector
{
	public $genericConnector;
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return $this->genericConnector;
	}
}