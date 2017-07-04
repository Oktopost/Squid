<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\OneToOne;


use lib\DataSet;
use lib\TDBAssert;

use Objection\LiteSetup;
use Objection\LiteObject;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Impl\Connectors\Object\Join\JoinConnectors\ByProperties;
use Squid\MySql\Impl\Connectors\Object\Generic\GenericIdentityConnector;


class OneToOneIdentityConnectorTest extends TestCase
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
		$mainConnector->setObjectMap(OneToOneIdentParent::class, ['child']);
		$mainConnector->setTable($this->tableA);
		
		$childConnector = new GenericIdentityConnector();
		$childConnector->setConnector(DataSet::connector());
		$childConnector->setPrimaryKeys(['aa']);
		$childConnector->setObjectMap(OneToOneIdentChild::class);
		$childConnector->setTable($this->tableB);
		
		$join = new ByProperties();
		$join->setConnector($childConnector);
		$join->setProperties(['a' => 'pa']);
		$join->setParentReferenceProperty('child');
		
		$subject = (new OneToOneIdentityConnector())
			->setPrimaryConnector($mainConnector)
			->setConfig($join);
		
		return $subject;
	}
	
	
	public function test_update_CountIsCorrect()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		
		$child1 = new OneToOneIdentChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneIdentParent();
		$parent1->a = 1;
		$parent1->b = 'new_2';
		$parent1->child = $child1;
		
		$res = $subject->update($parent1);
		
		self::assertEquals(2, $res);
	}
	
	public function test_update_NoChild_CountIsCorrect()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]]);
		
		$parent1 = new OneToOneIdentParent();
		$parent1->a = 1;
		$parent1->b = 'new_2';
		$parent1->child = null;
		
		$res = $subject->update($parent1);
		
		self::assertEquals(1, $res);
	}
	
	public function test_update_ObjectsUpdated()
	{
		$subject = $this->subject([['a' => 1, 'b' => 1], ['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		
		$child1 = new OneToOneIdentChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneIdentParent();
		$parent1->a = 1;
		$parent1->b = 'new_1';
		$parent1->child = $child1;
		
		$subject->update($parent1);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
		
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 'new_1']);
		self::assertRowExists($this->tableA, ['a' => 2, 'b' => '3']);
		self::assertRowExists($this->tableB, ['c' => '15']);
	}
	
	
	public function test_upsert_CountIsCorrect()
	{
		$subject = $this->subject([['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		
		$child1 = new OneToOneIdentChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneIdentParent();
		$parent1->a = 1;
		$parent1->b = 'new_2';
		$parent1->child = $child1;
		
		$res = $subject->upsert($parent1);
		
		self::assertEquals(3, $res);
	}
	
	public function test_upsert_NoChild_CountIsCorrect()
	{
		$subject = $this->subject([['a' => 2, 'b' => 3]]);
		
		$parent1 = new OneToOneIdentParent();
		$parent1->a = 1;
		$parent1->b = 'new_2';
		$parent1->child = null;
		
		$res = $subject->upsert($parent1);
		
		self::assertEquals(1, $res);
	}
	
	public function test_upsert_ObjectsUpserted()
	{
		$subject = $this->subject([['a' => 2, 'b' => 3]], ['aa' => 11, 'pa' => 1, 'c' => 4]);
		
		$child1 = new OneToOneIdentChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneIdentParent();
		$parent1->a = 1;
		$parent1->b = 'new_1';
		$parent1->child = $child1;
		
		$subject->upsert($parent1);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
		
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 'new_1']);
		self::assertRowExists($this->tableA, ['a' => 2, 'b' => '3']);
		self::assertRowExists($this->tableB, ['c' => '15']);
	}
	
	
	public function test_insert_CountIsCorrect()
	{
		$subject = $this->subject([['a' => 2, 'b' => 3]], []);
		
		$child1 = new OneToOneIdentChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneIdentParent();
		$parent1->a = 1;
		$parent1->b = 'new_2';
		$parent1->child = $child1;
		
		$res = $subject->insert($parent1);
		
		self::assertEquals(2, $res);
	}
	
	public function test_insert_NoChild_CountIsCorrect()
	{
		$subject = $this->subject([['a' => 2, 'b' => 3]]);
		
		$parent1 = new OneToOneIdentParent();
		$parent1->a = 1;
		$parent1->b = 'new_2';
		$parent1->child = null;
		
		$res = $subject->insert($parent1);
		
		self::assertEquals(1, $res);
	}
	
	public function test_insert_ObjectsUpserted()
	{
		$subject = $this->subject([['a' => 2, 'b' => 3]]);
		
		$child1 = new OneToOneIdentChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$parent1 = new OneToOneIdentParent();
		$parent1->a = 1;
		$parent1->b = 'new_1';
		$parent1->child = $child1;
		
		$subject->insert($parent1);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(1, $this->tableB);
		
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => 'new_1']);
		self::assertRowExists($this->tableA, ['a' => 2, 'b' => '3']);
		self::assertRowExists($this->tableB, ['c' => '15']);
	}
	
	public function test_insert_ArrayOfObjects()
	{
		$subject = $this->subject();
		
		$child1 = new OneToOneIdentChild();
		$child1->aa = 11;
		$child1->c = '15';
		
		$child2 = new OneToOneIdentChild();
		$child2->aa = 12;
		$child2->c = '16';
		
		$parent1 = new OneToOneIdentParent();
		$parent1->a = 1;
		$parent1->b = '1';
		$parent1->child = $child1;
		
		$parent2 = new OneToOneIdentParent();
		$parent2->a = 2;
		$parent2->b = '2';
		$parent2->child = $child2;
		
		$subject->insert([$parent1, $parent2]);
		
		self::assertRowCount(2, $this->tableA);
		self::assertRowCount(2, $this->tableB);
		
		self::assertRowExists($this->tableA, ['a' => 1, 'b' => '1']);
		self::assertRowExists($this->tableA, ['a' => 2, 'b' => '2']);
		self::assertRowExists($this->tableB, ['pa' => '1', 'c' => 15]);
		self::assertRowExists($this->tableB, ['pa' => '2', 'c' => 16]);
	}
}



class OneToOneIdentChild extends LiteObject
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

class OneToOneIdentParent extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'a'		=> LiteSetup::createString(),
			'b'		=> LiteSetup::createString(),
			'child'	=> LiteSetup::createInstanceOf(OneToOneIdentChild::class)
		];
	}
}