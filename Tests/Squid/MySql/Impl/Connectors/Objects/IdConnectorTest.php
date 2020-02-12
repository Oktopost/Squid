<?php
namespace Squid\MySql\Impl\Connectors\Objects;


use lib\DataSet;
use lib\TDBAssert;
use lib\DummyObject;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Connectors\Objects\ID\IIdGenerator;
use Squid\MySql\Connectors\Objects\Generic\IGenericObjectConnector;


class IdConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	public function test_ReturnSelf()
	{
		$conn = new IdConnectorTestHelper();
		$conn->setTable('a')->setConnector(DataSet::connector());
		
		/** @noinspection PhpParamsInspection */
		self::assertEquals($conn, $conn->setGeneratedId('a', $this->createMock(IIdGenerator::class)));
		
		
		$conn = new IdConnectorTestHelper();
		$conn->setTable('a')->setConnector(DataSet::connector());
		
		self::assertEquals($conn, $conn->setAutoIncrementId('a'));
	}
	
	
	public function test_insert_NoInsertHandler_ConnectorCalled()
	{
		$generic = $this->getMockBuilder(IGenericObjectConnector::class)->getMock();
		
		$conn = new IdConnectorTestHelper();
		$conn->conn = $generic;
		$conn
			->setTable('a')
			->setConnector(DataSet::connector())
			->setIdKey('a');
		
		$target = [new DummyObject()];
		
		
		$generic
			->expects($this->once())
			->method('insertObjects')
			->with($target)
			->willReturn(123);
		
		
		$res = $conn->insert($target);
		
		self::assertEquals(123, $res);
	}
	
	public function test_insert_IdGenerator()
	{
		/** @var IIdGenerator|\PHPUnit_Framework_MockObject_MockObject $generator */
		$generator = $this->getMockBuilder(IIdGenerator::class)->getMock();
		$table = DataSet::table(['a', 'b']);
		
		$conn = new IdConnectorTestHelper();
		$conn
			->setTable($table)
			->setConnector(DataSet::connector())
			->setObjectMap(DummyObject::class)
			->setGeneratedId('a', $generator);
		
		$obj1 = new DummyObject(['b' => 1]);
		$obj2 = new DummyObject(['b' => 2]);
		
		$generator->method('generate')->willReturn(['ak', 'bk']);
		
		$res = $conn->insert([$obj1, $obj2]);
		
		self::assertEquals(2, $res);
		
		self::assertEquals('ak', $obj1->a);
		self::assertEquals('bk', $obj2->a);
		
		self::assertRowExists($table, ['a' => 'ak', 'b' => 1]);
		self::assertRowExists($table, ['a' => 'bk', 'b' => 2]);
	}
	
	public function test_insert_AutoIncId()
	{
		/** @var IIdGenerator|\PHPUnit_Framework_MockObject_MockObject $generator */
		$generator = $this->getMockBuilder(IIdGenerator::class)->getMock();
		$table = DataSet::table(['a', 'b']);
		
		$conn = new IdConnectorTestHelper();
		$conn
			->setTable($table)
			->setConnector(DataSet::connector())
			->setObjectMap(DummyObject::class)
			->setAutoIncrementId('a');
		
		DataSet::connector()->direct("ALTER TABLE {$table} ADD PRIMARY KEY (a), CHANGE `a` `a` INT(11) NOT NULL AUTO_INCREMENT")->executeDml();
		
		$obj1 = new DummyObject(['b' => 1]);
		$obj2 = new DummyObject(['b' => 2]);
		
		$generator->method('generate')->willReturn(['ak', 'bk']);
		
		$res = $conn->insert([$obj1, $obj2]);
		
		self::assertEquals(2, $res);
		
		self::assertEquals(1, $obj1->a);
		self::assertEquals(2, $obj2->a);
		
		self::assertRowExists($table, ['a' => 1, 'b' => 1]);
		self::assertRowExists($table, ['a' => 2, 'b' => 2]);
	}
	
	
	public function test_deleteById()
	{
		$generic = $this->getMockBuilder(IGenericObjectConnector::class)->getMock();
		$conn = new IdConnectorTestHelper();
		$conn->setIdKey('id', 'a');
		$conn->conn = $generic;
		
		$generic->expects($this->once())->method('deleteByFields')->with(['id' => 123])->willReturn(12);
		
		$res = $conn->deleteById(123);
		
		self::assertEquals(12, $res);
	}
	
	
	public function test_loadById()
	{
		$generic = $this->getMockBuilder(IGenericObjectConnector::class)->getMock();
		$conn = new IdConnectorTestHelper();
		$conn->setIdKey('id', 'a');
		$conn->conn = $generic;
		
		$generic->expects($this->once())->method('selectObjectByFields')->with(['id' => 123])->willReturn(12);
		
		$res = $conn->loadById(123);
		
		self::assertEquals(12, $res);
	}
}


class IdConnectorTestHelper extends IdConnector
{
	public $conn;
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return ($this->conn ?: parent::getGenericObjectConnector());
	}
	
	public function getObjectKeys()
	{
		return $this->getPrimaryKeys();
	}
}