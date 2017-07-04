<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\OneToOne;


use lib\DataSet;
use lib\TDBAssert;

use Objection\LiteSetup;
use Objection\LiteObject;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Impl\Connectors\Object\Generic\GenericIdConnector;
use Squid\MySql\Impl\Connectors\Object\Join\JoinConnectors\ByProperties;
use Squid\MySql\Impl\Connectors\Object\Generic\GenericIdentityConnector;


class OneToOneIdConnectorTest extends TestCase
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
		
		
		$mainConnector = new GenericIdConnector();
		$mainConnector->setConnector(DataSet::connector());
		$mainConnector->setIdKey('a');
		$mainConnector->setObjectMap(OneToOneIdParent::class, ['child']);
		$mainConnector->setTable($this->tableA);
		
		$childConnector = new GenericIdConnector();
		$childConnector->setConnector(DataSet::connector());
		$childConnector->setIdKey('aa');
		$childConnector->setObjectMap(OneToOneIdChild::class);
		$childConnector->setTable($this->tableB);
		
		$join = new ByProperties();
		$join->setConnector($childConnector);
		$join->setProperties(['a' => 'pa']);
		$join->setParentReferenceProperty('child');
		
		$subject = (new OneToOneIdConnector())
			->setPrimaryConnector($mainConnector)
			->setConfig($join);
		
		return $subject;
	}
	
	
	public function test_delete_DeleteByOneId()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]]);
		
		$res = $subject->deleteById(1);
		
		self::assertEquals(1, $res);
		self::assertRowCount(1, $this->tableA);
	}
	
	public function test_delete_DeleteByArray()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3], ['a' => 3, 'b' => 3]]);
		
		$res = $subject->deleteById([1, 2]);
		
		self::assertEquals(2, $res);
		self::assertRowCount(1, $this->tableA);
	}
	
	
	public function test_load_NotFound_ReturnNull()
	{
		$subject = $this->subject();
		
		$res = $subject->loadById(1);
		
		self::assertNull($res);
	}
	
	public function test_load_QueryByArrayNotFound_ReturnEmptyArray()
	{
		$subject = $this->subject();
		
		$res = $subject->loadById([1, 2]);
		
		self::assertEquals([], $res);
	}
	
	public function test_load_ById_ObjectReturned()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2]], [['aa' => 2, 'pa' => 1, 'c' => 4]]);
		
		$res = $subject->loadById(1);
		
		self::assertInstanceOf(OneToOneIdParent::class, $res);
		self::assertInstanceOf(OneToOneIdChild::class, $res->child);
	}
	
	public function test_load_ByArray_ObjectReturned()
	{
		$subject = $this->subject([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 3]], [['aa' => 2, 'pa' => 1, 'c' => 4]]);
		
		$res = $subject->loadById([1, 2]);
		
		self::assertCount(2, $res);
		
		self::assertInstanceOf(OneToOneIdParent::class, $res[0]);
		self::assertInstanceOf(OneToOneIdChild::class, $res[0]->child);
		self::assertEquals(1, $res[0]->a);
		
		self::assertInstanceOf(OneToOneIdParent::class, $res[1]);
		self::assertNull($res[1]->child);
		self::assertEquals(2, $res[1]->a);
	}
}



class OneToOneIdChild extends LiteObject
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

class OneToOneIdParent extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'a'		=> LiteSetup::createString(),
			'b'		=> LiteSetup::createString(),
			'child'	=> LiteSetup::createInstanceOf(OneToOneIdChild::class)
		];
	}
}