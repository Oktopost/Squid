<?php
namespace Squid\MySql\Impl\Connectors\Internal\Generic;


use Squid\MySql\Connectors\Table\ITableNameConnector;
use Squid\MySql\Impl\Connectors\Generic\InsertConnector;
use Squid\MySql\IMySqlConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;
use Squid\MySql\Command\ICmdInsert;

use PHPUnit\Framework\TestCase;


class InsertConnectorTest extends TestCase
{
	/** @var ICmdInsert|\PHPUnit_Framework_MockObject_MockObject */
	private $insert;
	
	/** @var AbstractSingleTableConnector|\PHPUnit_Framework_MockObject_MockObject */
	private $connector;
	
	
	private function mockInsert()
	{
		$this->insert = $this->getMockBuilder(ICmdInsert::class)->getMock();
	}
	
	private function mockConnector()
	{
		$connector = $this->getMockBuilder(IMySqlConnector::class)->getMock();
		$connector->method('insert')->willReturn($this->insert);
		
		$tableName = $this->getMockBuilder(ITableNameConnector::class)->getMock();
		$tableName->method('name')->willReturn('table');
		
		$this->connector = $this->getMockBuilder(AbstractSingleTableConnector::class)->getMock();
		$this->connector->method('getConnector')->willReturn($connector);
		$this->connector->method('getTableName')->willReturn('table');
		$this->connector->method('getTable')->willReturn($tableName);
	}
	
	private function mockData($fields, $ignore, $data, $result)
	{
		$this->insert
			->expects($this->once())
			->method('into')
			->with('table', $fields)
			->willReturnSelf();
		
		$this->insert
			->expects($this->once())
			->method('ignore')
			->with($ignore)
			->willReturnSelf();
		
		$this->insert
			->expects($this->once())
			->method('valuesBulk')
			->with($data)
			->willReturnSelf();
		
		$this->insert
			->expects($this->once())
			->method('executeDml')
			->willReturn($result);
	}
	
	
	public function setUp()
	{
		$this->mockInsert();
		$this->mockConnector();
	}
	
	
	public function test_row_WithIgnore()
	{
		$insertConnector = new InsertConnector($this->connector);
		$this->mockData(null, false, [['a' => 1, 'b' => 2]], 1);
		self::assertEquals(1, $insertConnector->row(['a' => 1, 'b' => 2]));
	}
	
	public function test_row_NoIgnore()
	{
		$insertConnector = new InsertConnector($this->connector);
		$this->mockData(null, true, [['a' => 1, 'b' => 2]], 1);
		self::assertEquals(1, $insertConnector->row(['a' => 1, 'b' => 2], true));
	}
}