<?php
namespace Squid\MySql\Impl\Connectors\Object;


use PHPUnit\Framework\TestCase;
use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;


class IdConnectorTest extends TestCase
{
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
		
		$generic->expects($this->once())->method('selectObjectsByFields')->with(['id' => 123])->willReturn(12);
		
		$res = $conn->loadById(123);
		
		self::assertEquals(12, $res);
	}
}


class IdConnectorTestHelper extends IdConnector
{
	public $conn;
	
	
	protected function getGenericObjectConnector(): IGenericObjectConnector
	{
		return $this->conn;
	}
	
	public function getObjectKeys()
	{
		return $this->getPrimaryKeys();
	}
}