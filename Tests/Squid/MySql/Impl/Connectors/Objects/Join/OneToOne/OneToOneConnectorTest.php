<?php
namespace Squid\MySql\Impl\Connectors\Objects\Join\OneToOne;


use lib\DataSet;
use lib\TDBAssert;
use Objection\LiteObject;
use Objection\LiteSetup;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Connectors\Objects\Query\ICmdObjectSelect;
use Squid\MySql\Impl\Connectors\Objects\Generic\GenericIdentityConnector;
use Squid\MySql\Impl\Connectors\Objects\Join\JoinConnectors\ByProperties;
use Squid\OrderBy;


class OneToOneConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $tableA;
	private $tableB;
	
	
	private function subject($dataA = [], $dataB = [])
	{
		$this->tableA = DataSet::table(['a', 'b'], $dataA);
		$this->tableB = DataSet::table(['aa', 'pa', 'c'], $dataB);
		
		DataSet::connector()
			->direct("ALTER TABLE {$this->tableA} ADD PRIMARY KEY (a)")->executeDml();
		
		DataSet::connector()
			->direct("ALTER TABLE {$this->tableB} ADD PRIMARY KEY (aa)")->executeDml();
		
		
		$mainConnector = new GenericIdentityConnector();
		$mainConnector->setConnector(DataSet::connector());
		$mainConnector->setPrimaryKeys(['a']);
		$mainConnector->setObjectMap(OneToOneParent::class, ['child']);
		$mainConnector->setTable($this->tableA);
		
		$childConnector = new GenericIdentityConnector();
		$childConnector->setConnector(DataSet::connector());
		$childConnector->setPrimaryKeys(['aa']);
		$childConnector->setObjectMap(OneToOneChild::class);
		$childConnector->setTable($this->tableB);
		
		$join = new ByProperties();
		$join->setConnector($childConnector);
		$join->setProperties(['a' => 'pa']);
		$join->setParentReferenceProperty('child');
		
		$subject = (new OneToOneConnector())
			->setPrimaryConnector($mainConnector)
			->setConfig($join);
		
		return $subject;
	}
	
	
	public function test_countByField_sanity()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4], ['a' => 4, 'b' => 2]]);
		self::assertEquals(2, $subject->countByField('b', 2));
	}
	
	public function test_countByFields_sanity()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4], ['a' => 4, 'b' => 2]]);
		self::assertEquals(2, $subject->countByFields(['b' => 2]));
	}
	
	
	public function test_existsByField_sanity()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4], ['a' => 4, 'b' => 2]]);
		self::assertTrue($subject->existsByField('b', 2));
		self::assertFalse($subject->existsByField('b', 3));
	}
	
	public function test_existsByFields_sanity()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4], ['a' => 4, 'b' => 2]]);
		self::assertTrue($subject->existsByFields(['b' => 2]));
		self::assertFalse($subject->existsByFields(['b' => 3]));
	}
	
	
	public function test_deleteByField_sanity()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4], ['a' => 4, 'b' => 2]]);
		self::assertEquals(2, $subject->deleteByField('b', 2));
		self::assertRowExists($this->tableA, ['b' => 4]);
	}
	
	public function test_deleteByFields_sanity()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4], ['a' => 4, 'b' => 2]]);
		self::assertEquals(2, $subject->deleteByFields(['b' => 2]));
		self::assertRowExists($this->tableA, ['b' => 4]);
	}
	
	
	public function test_insertObjects_CountForParentsAndChildrenReturned()
	{
		$subject = $this->subject();
		
		$child1 = new OneToOneChild();
		$child1->aa = 1;
		$child1->c = 'ccc';
		
		$parent1 = new OneToOneParent();
		$parent1->a = 11;
		$parent1->b = 'b1';
		$parent1->child = $child1;
		
		$parent2 = new OneToOneParent();
		$parent2->a = 12;
		$parent2->b = 'b2';
		$parent2->child = null;
		
		self::assertEquals(3, $subject->insertObjects([$parent1, $parent2]));
	}
	
	public function test_insertObjects_AllObjectsInserted()
	{
		$subject = $this->subject();
		
		$child1 = new OneToOneChild();
		$child1->aa = 1;
		$child1->c = 'ccc';
		
		$parent1 = new OneToOneParent();
		$parent1->a = 11;
		$parent1->b = 'b1';
		$parent1->child = $child1;
		
		$parent2 = new OneToOneParent();
		$parent2->a = 12;
		$parent2->b = 'b2';
		$parent2->child = null;
		
		$subject->insertObjects([$parent1, $parent2]);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
		
		self:self::assertRowExists($this->tableB, ['pa' => 11]);
	}
	
	public function test_insertObjects_IgnoreFlagUsed()
	{
		$subject = $this->subject([], ['aa' => 11, 'pa' => 13, 'c' => 4]);
		
		$child1 = new OneToOneChild();
		$child1->aa = 11;
		$child1->c = 'ccc';
		
		$parent1 = new OneToOneParent();
		$parent1->a = 11;
		$parent1->b = 'b1';
		$parent1->child = $child1;
		
		$parent2 = new OneToOneParent();
		$parent2->a = 12;
		$parent2->b = 'b2';
		$parent2->child = null;
		
		$subject->insertObjects($parent1, true);
		
		self::assertRowCount(1, $this->tableB);
		self:self::assertRowExists($this->tableB, ['c' => 4]);
	}
	
	
	public function test_selectObjectByField_NotFound_ReturnNull()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectObjectByField('b', 4);
		self::assertNull($res);
	}
	
	public function test_selectObjectByField_ObjectWithoutChild()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectObjectByField('b', 3);
		
		self::assertInstanceOf(OneToOneParent::class, $res);
		self::assertNull($res->child);
	}
	
	public function test_selectObjectByField_ObjectWithChild()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectObjectByField('b', 2);
		
		self::assertInstanceOf(OneToOneParent::class, $res);
		self::assertInstanceOf(OneToOneChild::class, $res->child);
	}
	
	
	public function test_selectObjectByFields_NotFound_ReturnNull()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectObjectByFields(['b' => 4]);
		self::assertNull($res);
	}
	
	public function test_selectObjectByFields_ObjectWithoutChild()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectObjectByFields(['b' => 3]);
		
		self::assertInstanceOf(OneToOneParent::class, $res);
		self::assertNull($res->child);
	}
	
	public function test_selectObjectByFields_ObjectWithChild()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectObjectByFields(['b' => 2]);
		
		self::assertInstanceOf(OneToOneParent::class, $res);
		self::assertInstanceOf(OneToOneChild::class, $res->child);
	}
	
	
	public function test_selectFirstObjectByField_NotFound_ReturnNull()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectFirstObjectByField('b', 4);
		self::assertNull($res);
	}
	
	public function test_selectFirstObjectByField_ObjectWithoutChild()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2]], ['aa' => 11, 'pa' => 2, 'c' => 4]);
		$res = $subject->selectFirstObjectByField('b', 2);
		
		self::assertInstanceOf(OneToOneParent::class, $res);
		self::assertNull($res->child);
	}
	
	public function test_selectFirstObjectByField_ObjectWithChild()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectFirstObjectByField('b', 2);
		
		self::assertInstanceOf(OneToOneParent::class, $res);
		self::assertInstanceOf(OneToOneChild::class, $res->child);
	}
	
	
	public function test_selectFirstObjectByFields_NotFound_ReturnNull()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectFirstObjectByFields(['b' => 4]);
		self::assertNull($res);
	}
	
	public function test_selectFirstObjectByFields_ObjectWithoutChild()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2]], ['aa' => 11, 'pa' => 2, 'c' => 4]);
		$res = $subject->selectFirstObjectByFields(['b' => 2]);
		
		self::assertInstanceOf(OneToOneParent::class, $res);
		self::assertNull($res->child);
	}
	
	public function test_selectFirstObjectByFields_ObjectWithChild()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectFirstObjectByFields(['b' => 2]);
		
		self::assertInstanceOf(OneToOneParent::class, $res);
		self::assertInstanceOf(OneToOneChild::class, $res->child);
	}
	
	
	public function test_selectObjectsByFields_NotFound_ReturnEmptyArray()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectObjectsByFields(['b' => 5]);
		self::assertEquals([], $res);
	}
	
	public function test_selectObjectsByFields()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2], ['a' => 3, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectObjectsByFields(['b' => 2]);
		
		self::assertCount(2, $res);
		self::assertInstanceOf(OneToOneParent::class, $res[0]);
		self::assertInstanceOf(OneToOneParent::class, $res[1]);
		
		self::assertInstanceOf(OneToOneChild::class, $res[0]->child);
		self::assertNull($res[1]->child);
	}
	
	
	public function test_selectObjects_NotFound_ReturnEmptyArray()
	{
		$subject = $this->subject();
		$res = $subject->selectObjects();
		self::assertEquals([], $res);
	}
	
	public function test_selectObjects_AllOBjectsSelected()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		$res = $subject->selectObjects();
		
		self::assertCount(2, $res);
		self::assertInstanceOf(OneToOneParent::class, $res[0]);
		self::assertInstanceOf(OneToOneParent::class, $res[1]);
		
		self::assertInstanceOf(OneToOneChild::class, $res[0]->child);
		self::assertNull($res[1]->child);
	}
	
	public function test_selectObjects_DataIsOrdered()
	{
		$subject = $this->subject([['a' => 1, 'b' => -1000], ['a' => 2, 'b' => 1000]]);
		
		$res = $subject->selectObjects(['b'], OrderBy::DESC);
		self::assertEquals(2, $res[0]->a);
		self::assertEquals(1, $res[1]->a);
		
		$res = $subject->selectObjects(['b'], OrderBy::ASC);
		self::assertEquals(1, $res[0]->a);
		self::assertEquals(2, $res[1]->a);
	}
	
	
	public function test_updateObject_CountIsCorrect()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		
		$child1 = new OneToOneChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneParent();
		$parent1->a = 1;
		$parent1->b = 'new_2';
		$parent1->child = $child1;
		
		$res = $subject->updateObject($parent1, ['b']);
		
		self::assertEquals(1, $res);
	}
	
	public function test_updateObject_ObjectsUpdated()
	{
		$subject = $this->subject([['a' => 1, 'b' => 1], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		
		$child1 = new OneToOneChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneParent();
		$parent1->a = 1;
		$parent1->b = 'new_1';
		$parent1->child = $child1;
		
		$subject->updateObject($parent1, ['a']);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
		
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 'new_1']);
		self::assertRowExists($this->tableA, ['a' => 2, 'b' => '3']);
		self::assertRowExists($this->tableB, ['c' => '15']);
	}
	
	
	public function test_upsertObjectsByKeys_CountIsCorrect()
	{
		$subject = $this->subject([['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		
		$child1 = new OneToOneChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneParent();
		$parent1->a = 1;
		$parent1->b = 'new_2';
		$parent1->child = $child1;
		
		$res = $subject->upsertObjectsByKeys($parent1, ['a']);
		
		self::assertEquals(3, $res);
	}
	
	public function test_upsertObjectsByKeys_ObjectsUpdated()
	{
		$subject = $this->subject([['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		
		$child1 = new OneToOneChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneParent();
		$parent1->a = 1;
		$parent1->b = 'new_1';
		$parent1->child = $child1;
		
		$subject->upsertObjectsByKeys($parent1, ['a']);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
		
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 'new_1']);
		self::assertRowExists($this->tableA, ['a' => 2, 'b' => '3']);
		self::assertRowExists($this->tableB, ['c' => '15']);
	}
	
	
	public function test_upsertObjectsForValues_CountIsCorrect()
	{
		$subject = $this->subject([['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		
		$child1 = new OneToOneChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneParent();
		$parent1->a = 1;
		$parent1->b = 'new_2';
		$parent1->child = $child1;
		
		$res = $subject->upsertObjectsForValues($parent1, ['b']);
		
		self::assertEquals(3, $res);
	}
	
	public function test_upsertObjectsForValues_ObjectsUpdated()
	{
		$subject = $this->subject([['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		
		$child1 = new OneToOneChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneParent();
		$parent1->a = 1;
		$parent1->b = 'new_1';
		$parent1->child = $child1;
		
		$subject->upsertObjectsForValues($parent1, ['b']);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
		
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 'new_1']);
		self::assertRowExists($this->tableA, ['a' => 2, 'b' => '3']);
		self::assertRowExists($this->tableB, ['c' => '15']);
	}
	
	public function test_query_QueryObjectReturned()
	{
		$subject = $this->subject([['a' => 1, 'b' => 1]], ['aa' => 11, 'pa' => 1, 'c' => 2]);
		self::assertInstanceOf(ICmdObjectSelect::class, $subject->query());
	}
	
	public function test_query_QueryFirst()
	{
		$subject = $this->subject([['a' => 1, 'b' => 1]], ['aa' => 11, 'pa' => 1, 'c' => 2]);
		$res = $subject->query()->byField('a', 1)->queryFirst();
		
		self::assertEquals(1, $res->a);
		self::assertEquals(11, $res->child->aa);
	}
	
	public function test_query_QueryOne()
	{
		$subject = $this->subject([['a' => 1, 'b' => 1]], ['aa' => 11, 'pa' => 1, 'c' => 2]);
		$res = $subject->query()->byField('a', 1)->queryOne();
		
		self::assertEquals(1, $res->a);
		self::assertEquals(11, $res->child->aa);
	}
	
	public function test_query_QueryMap()
	{
		$subject = $this->subject([['a' => 1, 'b' => 1], ['a' => 2, 'b' => 2]], ['aa' => 11, 'pa' => 1, 'c' => 2]);
		$res = $subject->query()->orderBy('a')->queryMapRow('b');
		
		self::assertEquals(1, $res[1]->a);
		self::assertEquals(11, $res[1]->child->aa);
		
		self::assertEquals(2, $res[2]->a);
		self::assertNull($res[2]->child);
	}
	
	public function test_query_QueryAll()
	{
		$subject = $this->subject([['a' => 1, 'b' => 1], ['a' => 2, 'b' => 2]], ['aa' => 11, 'pa' => 1, 'c' => 2]);
		$res = $subject->query()->orderBy('a')->queryAll();
		
		self::assertEquals(1, $res[0]->a);
		self::assertEquals(11, $res[0]->child->aa);
		
		self::assertEquals(2, $res[1]->a);
		self::assertNull($res[1]->child);
	}
	
	public function test_query_QueryIterator_ExceptionThrown()
	{
		$this->expectException(\Squid\Exceptions\SquidException::class);
		
		$subject = $this->subject();
		$subject->query()->orderBy('a')->queryIterator();
	}
	
	public function test_query_queryWithCallback_ExceptionThrown()
	{
		$this->expectException(\Squid\Exceptions\SquidException::class);
		
		$subject = $this->subject();
		$subject->query()->orderBy('a')->queryWithCallback(function() {});
	}
	
	public function test_query_EmptyTable()
	{
		$subject = $this->subject();
		
		self::assertNull($subject->query()->byField('a', 1)->queryFirst());
		self::assertNull($subject->query()->byField('a', 1)->queryOne());
		
		self::assertEquals([], $subject->query()->byField('a', 1)->queryMapRow('a'));
		self::assertEquals([], $subject->query()->byField('a', 1)->queryAll());
	}
}



class OneToOneChild extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'aa'	=> LiteSetup::createString(),
			'pa'	=> LiteSetup::createString(),
			'c'		=> LiteSetup::createString(),
		];
	}
}

class OneToOneParent extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'a'		=> LiteSetup::createString(),
			'b'		=> LiteSetup::createString(),
			'child'	=> LiteSetup::createInstanceOf(OneToOneChild::class)
		];
	}
}