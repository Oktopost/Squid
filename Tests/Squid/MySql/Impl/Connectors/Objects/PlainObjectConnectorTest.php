<?php
namespace Squid\MySql\Impl\Connectors\Objects;


use lib\DataSet;
use lib\TDBAssert;
use Objection\LiteObject;
use Objection\LiteSetup;
use PHPUnit\Framework\TestCase;
use Squid\OrderBy;


class PlainObjectConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $table;
	
	
	private function newObject($a, $b): PlainObjectHelper
	{
		$result = new PlainObjectHelper();
		
		$result->a = $a;
		$result->b = $b;
		
		self::$LAST_ROW = $result->toArray();
		
		return $result;
	}
	
	private function subject(array $data = []): PlainObjectConnector
	{
		$this->table = DataSet::table(['a', 'b'], $data);
		
		DataSet::connector()
			->direct("ALTER TABLE {$this->table} ADD PRIMARY KEY (a), CHANGE `a` `a` INT(11) NOT NULL AUTO_INCREMENT")
			->executeDml();
		
		$connector = new PlainObjectConnector();
		$connector->setConnector(DataSet::connector());
		$connector->setObjectMap(PlainObjectHelper::class);
		$connector->setTable($this->table);
		
		return $connector;
	}
	
	
	public function test_insertObjects_EmptyTable_ObjectInserted()
	{
		$this->subject()->insertObjects($this->newObject(1, 2));
		self::assertRowExists($this->table, self::$LAST_ROW);
	}
	
	public function test_insertObjects_OtherObjectsExist_ObjectsNotModifed()
	{
		$this->subject($this->row(1, 2))->insertObjects($this->newObject(3, 4));
		self::assertRowExists($this->table, $this->row(1, 2));
		self::assertLastRowExists($this->table);
	}

	/**
	 * @expectedException \Squid\MySql\Exceptions\MySqlException
	 */
	public function test_insertObjects_ObjectWithSameKeyExists_ErrorThrown()
	{
		$this->subject($this->row(1, 2))->insertObjects($this->newObject(1, 4));
	}
	
	public function test_insertObjects_ObjectWithSameKeyExistsWithIgnoreFlagOn_NoErrorAndRowNotModified()
	{
		$this->subject($this->row(1, 2))->insertObjects($this->newObject(1, 4), true);
		self::assertRowExists($this->table, $this->row(1, 2));
	}
	
	public function test_insertObjects_OneObjectInserted_CorrectCountReturned()
	{
		$res = $this->subject()->insertObjects($this->newObject(1, 2));
		self::assertEquals(1, $res);
	}
	
	public function test_insertObjects_NumberOfObjectysInserted_CorrectCountReturned()
	{
		$res = $this->subject()->insertObjects([$this->newObject(1, 2), $this->newObject(3, 4)]);
		self::assertEquals(2, $res);
	}
	
	public function test_insertObjects_InsertingMultipleObjectsWithIgnoreFlagOn_CountIsCorrect()
	{
		$res = $this->subject($this->row(1, 2))
			->insertObjects([$this->newObject(1, 4), $this->newObject(2, 3), $this->newObject(5, 6)], true);
		
		self::assertEquals(2, $res);
	}
	
	public function test_insertObjects_InsertingMultipleObjectsWithIgnoreFlagOn_OnlyNonDuplicatesInserted()
	{
		$this->subject($this->row(1, 2))
			->insertObjects([$this->newObject(1, 4), $this->newObject(2, 3), $this->newObject(5, 6)], true);
		
		self::assertRowCount(3, $this->table);
		self::assertRowExists($this->table, $this->row(1, 2));
		self::assertRowExists($this->table, $this->row(2, 3));
		self::assertRowExists($this->table, $this->row(5, 6));
	}
	
	
	public function test_selectObjectByFields_ObjectNotFound_ReturnNull()
	{
		$res = $this->subject($this->row(1, 2))->selectObjectByFields(['a' => 3]);
		self::assertNull($res);
	}
	
	public function test_selectObjectByFields_ObjectFound_ObjectReturned()
	{
		$res = $this->subject($this->row(1, 2))->selectObjectByFields(['a' => 1]);
		
		self::assertInstanceOf(PlainObjectHelper::class, $res);
		self::assertEquals(self::$LAST_ROW, $res->toArray());
	}
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_selectObjectByFields_NumberOfMatchingObjects_ExceptionThrown()
	{
		$this->subject([$this->row(1, 2), $this->row(2, 2)])->selectObjectByFields(['b' => 2]);
	}
	
	
	public function test_selectObjectByField_ObjectNotFound_ReturnNull()
	{
		$res = $this->subject($this->row(1, 2))->selectObjectByField('a', 3);
		self::assertNull($res);
	}
	
	public function test_selectObjectByField_ObjectFound_ObjectReturned()
	{
		$res = $this->subject($this->row(1, 2))->selectObjectByField('a', 1);
		
		self::assertInstanceOf(PlainObjectHelper::class, $res);
		self::assertEquals(self::$LAST_ROW, $res->toArray());
	}
	
	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_selectObjectByField_NumberOfMatchingObjects_OnlyFirstObjectReturned()
	{
		$this->subject([$this->row(1, 2), $this->row(2, 2)])->selectObjectByField('b', 2);
	}
	
	
	public function test_selectFirstObjectByFields_ObjectNotFound_ReturnNull()
	{
		$res = $this->subject($this->row(1, 2))->selectFirstObjectByFields(['a' => 3]);
		self::assertNull($res);
	}
	
	public function test_selectFirstObjectByFields_ObjectFound_ObjectReturned()
	{
		$res = $this->subject($this->row(1, 2))->selectFirstObjectByFields(['a' => 1]);
		
		self::assertInstanceOf(PlainObjectHelper::class, $res);
		self::assertEquals(self::$LAST_ROW, $res->toArray());
	}
	
	public function test_selectFirstObjectByFields_NumberOfMatchingObjects_OnlyFirstObjectReturned()
	{
		$res = $this->subject([$this->row(1, 2), $this->row(2, 2)])->selectFirstObjectByFields(['b' => 2]);
		
		self::assertInstanceOf(PlainObjectHelper::class, $res);
		self::assertEquals($this->row(1, 2), $res->toArray());
	}
	
	
	public function test_selectFirstObjectByField_ObjectNotFound_ReturnNull()
	{
		$res = $this->subject($this->row(1, 2))->selectFirstObjectByField('a', 3);
		self::assertNull($res);
	}
	
	public function test_selectFirstObjectByField_ObjectFound_ObjectReturned()
	{
		$res = $this->subject($this->row(1, 2))->selectFirstObjectByField('a', 1);
		
		self::assertInstanceOf(PlainObjectHelper::class, $res);
		self::assertEquals(self::$LAST_ROW, $res->toArray());
	}
	
	public function test_selectFirstObjectByField_NumberOfMatchingObjects_OnlyFirstObjectReturned()
	{
		$res = $this->subject([$this->row(1, 2), $this->row(2, 2)])->selectFirstObjectByField('b', 2);
		
		self::assertInstanceOf(PlainObjectHelper::class, $res);
		self::assertEquals($this->row(1, 2), $res->toArray());
	}
	
	
	public function test_selectFirstObjectsByField_ObjectNotFound_ReturnEmptyArray()
	{
		$res = $this->subject($this->row(1, 2))->selectObjectsByFields(['a' => 3]);
		self::assertEquals([], $res);
	}
	
	public function test_selectFirstObjectsByField_ObjectsFound_ObjectsReturned()
	{
		$res = $this->subject([$this->row(1, 2), $this->row(3, 2)])->selectObjectsByFields(['b' => 2]);
		self::assertCount(2, $res);
		self::assertEquals([$this->row(1, 2), $this->row(3, 2)], PlainObjectHelper::allToArray($res));
	}
	
	public function test_selectFirstObjectsByField_ObjectsFoundWithLimit_OnlyFirstNElementsReturned()
	{
		$res = $this->subject([$this->row(1, 2), $this->row(3, 2)])->selectObjectsByFields(['b' => 2], 1);
		
		self::assertCount(1, $res);
		self::assertEquals([$this->row(1, 2)], PlainObjectHelper::allToArray($res));
	}
	
	
	public function test_selectObjects_EmptyTable_ReturnEmptyArray()
	{
		$res = $this->subject()->selectObjects();
		self::assertEquals([], $res);
	}
	
	public function test_selectObjects_TableHasValue_AllValuesReturned()
	{
		$res = $this->subject([$this->row(1, 2), $this->row(3, 4)])->selectObjects();
		
		self::assertCount(2, $res);
		self::assertEquals([$this->row(1, 2), $this->row(3, 4)], PlainObjectHelper::allToArray($res));
	}
	
	public function test_selectObjects_OrderByUsed_DataReturnedOrderByRequestedField()
	{
		$res = $this->subject([$this->row(1, 2), $this->row(3, 4)])->selectObjects(['b'], OrderBy::DESC);
		
		self::assertCount(2, $res);
		self::assertEquals([$this->row(3, 4), $this->row(1, 2)], PlainObjectHelper::allToArray($res));
	}
	
	
	public function test_updateObject_EmptyTable_NoObjectInserted()
	{
		$this->subject()->updateObject($this->newObject(1, 2), ['a']);
		self::assertRowCount(0, $this->table);
	}
	
	public function test_updateObject_EmptyTable_Return0()
	{
		$res = $this->subject()->updateObject($this->newObject(1, 2), ['a']);
		self::assertEquals(0, $res);
	}
	
	public function test_updateObject_DifferentObjectsExists_OtherObjectsNotAffected()
	{
		$this->subject($this->row(1, 2))->updateObject($this->newObject(2, 3), ['a']);
		
		self::assertRowCount(1, $this->table);
		self::assertRowExists($this->table, $this->row(1, 2));
	}
	
	public function test_updateObject_MatchingObjectExists_ObjectsUpdated()
	{
		$this->subject([$this->row(1, 2), $this->row(2, 4)])->updateObject($this->newObject(1, 3), ['a']);
		
		self::assertRowCount(2, $this->table);
		self::assertRowExists($this->table, $this->row(1, 3));
		self::assertRowExists($this->table, $this->row(2, 4));
	}
	
	public function test_updateObject_MatchingObjectExists_CorrectCountReturned()
	{
		$res = $this->subject([$this->row(1, 2), $this->row(2, 4)])->updateObject($this->newObject(1, 3), ['a']);
		self::assertEquals(1, $res);
	}
	
	
	public function test_upsertObjectsByKeys_EmptyTable_ObjectInserted()
	{
		$this->subject()->upsertObjectsByKeys($this->newObject(1, 2), ['a']);
		self::assertRowCount(1, $this->table);
		self::assertLastRowExists($this->table);
	}
	
	public function test_upsertObjectsByKeys_EmptyTable_ReturnCorrectCount()
	{
		$res = $this->subject()->upsertObjectsByKeys($this->newObject(1, 2), ['a']);
		self::assertEquals(1, $res);
	}
	
	public function test_upsertObjectsByKeys_DifferentObjectsExists_OtherObjectsNotAffected()
	{
		$this->subject($this->row(1, 2))->upsertObjectsByKeys($this->newObject(2, 3), ['a']);
		
		self::assertRowExists($this->table, $this->row(1, 2));
	}
	
	public function test_upsertObjectsByKeys_DifferentObjectsExists_NewObjectInserted()
	{
		$this->subject($this->row(1, 2))->upsertObjectsByKeys($this->newObject(2, 3), ['a']);
		
		self::assertRowCount(2, $this->table);
		self::assertRowExists($this->table, $this->row(2, 3));
	}
	
	public function test_upsertObjectsByKeys_MatchingObjectExists_ObjectsUpdated()
	{
		$this->subject([$this->row(1, 2), $this->row(2, 4)])->upsertObjectsByKeys($this->newObject(1, 3), ['a']);
		
		self::assertRowCount(2, $this->table);
		self::assertRowExists($this->table, $this->row(1, 3));
		self::assertRowExists($this->table, $this->row(2, 4));
	}
	
	public function test_upsertObjectsByKeys_MatchingObjectExists_CorrectCountReturned()
	{
		$res = $this->subject([$this->row(1, 2), $this->row(2, 4)])->upsertObjectsByKeys($this->newObject(1, 3), ['a']);
		
		self::assertEquals(2, $res);
	}
	
	
	public function test_upsertObjectsForValues_EmptyTable_ObjectInserted()
	{
		$this->subject()->upsertObjectsForValues($this->newObject(1, 2), ['b']);
		self::assertRowCount(1, $this->table);
		self::assertLastRowExists($this->table);
	}
	
	public function test_upsertObjectsForValues_EmptyTable_ReturnCorrectCount()
	{
		$res = $this->subject()->upsertObjectsForValues($this->newObject(1, 2), ['b']);
		self::assertEquals(1, $res);
	}
	
	public function test_upsertObjectsForValues_DifferentObjectsExists_OtherObjectsNotAffected()
	{
		$this->subject($this->row(1, 2))->upsertObjectsForValues($this->newObject(2, 3), ['b']);
		
		self::assertRowExists($this->table, $this->row(1, 2));
	}
	
	public function test_upsertObjectsForValues_DifferentObjectsExists_NewObjectInserted()
	{
		$this->subject($this->row(1, 2))->upsertObjectsForValues($this->newObject(2, 3), ['b']);
		
		self::assertRowCount(2, $this->table);
		self::assertRowExists($this->table, $this->row(2, 3));
	}
	
	public function test_upsertObjectsForValues_MatchingObjectExists_ObjectsUpdated()
	{
		$this->subject([$this->row(1, 2), $this->row(2, 4)])->upsertObjectsForValues($this->newObject(1, 3), ['b']);
		
		self::assertRowCount(2, $this->table);
		self::assertRowExists($this->table, $this->row(1, 3));
		self::assertRowExists($this->table, $this->row(2, 4));
	}
	
	public function test_upsertObjectsForValues_MatchingObjectExists_CorrectCountReturned()
	{
		$res = $this->subject([$this->row(1, 2), $this->row(2, 4)])->upsertObjectsForValues($this->newObject(1, 3), ['b']);
		
		self::assertEquals(2, $res);
	}
}


/**
 * @property string $a
 * @property string $b
 */
class PlainObjectHelper extends LiteObject
{
	protected function _setup()
	{
		return [
			'a' => LiteSetup::createInt(),
			'b' => LiteSetup::createInt()
		];
	}
}