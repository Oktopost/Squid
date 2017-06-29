<?php
namespace Squid\MySql\Impl\Connectors\Internal\Generic;


use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Connectors\Table\ITableNameConnector;
use Squid\MySql\Impl\Connectors\Generic\CountConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;

use PHPUnit\Framework\TestCase;


class CountConnectorTest extends TestCase
{
	
	/** @var ICmdSelect|\PHPUnit_Framework_MockObject_MockObject */
	private $select;
	
	/** @var AbstractSingleTableConnector|\PHPUnit_Framework_MockObject_MockObject */
	private $connector;
	
	
	private function mockSelect()
	{
		$this->select = $this->getMockBuilder(ICmdSelect::class)->getMock();
	}
	
	private function mockAbstractTableConnector()
	{
		$tableConnector = $this->getMockBuilder(ITableNameConnector::class)->getMock();
		$tableConnector->method('select')->willReturn($this->select);
		
		$this->connector = $this->getMockBuilder(AbstractSingleTableConnector::class)->getMock();
		$this->connector->method('getTable')->willReturn($tableConnector);
	}
	
	
	private function mockData($method, $result)
	{
		$this->select
			->expects($this->once())
			->method('byFields')
			->with(['a' => 1])
			->willReturnSelf();
		
		$this->select
			->expects($this->once())
			->method($method)
			->willReturn($result);
	}
	
	
	public function setUp()
	{
		$this->mockSelect();
		$this->mockAbstractTableConnector();
	}


	public function test_byFields()
	{
		$count = new CountConnector($this->connector);
		$this->mockData('queryCount', 123);
		self::assertEquals(123, $count->countByFields(['a' => 1]));
	}

	public function test_byField()
	{
		$count = new CountConnector($this->connector);
		$this->mockData('queryCount', 123);
		self::assertEquals(123, $count->countByField('a', 1));
	}


	public function test_existsByFields_ReturnTrue()
	{
		$count = new CountConnector($this->connector);
		$this->mockData('queryExists', true);
		self::assertEquals(true, $count->existsByFields(['a' => 1]));
	}
	
	public function test_existsByFields_ReturnFalse()
	{
		$count = new CountConnector($this->connector);
		$this->mockData('queryExists', false);
		self::assertEquals(false, $count->existsByFields(['a' => 1]));
	}


	public function test_existsByField_ReturnTrue()
	{
		$count = new CountConnector($this->connector);
		$this->mockData('queryExists', true);
		self::assertEquals(true, $count->existsByField('a', 1));
	}
	
	public function test_existsByField_ReturnFalse()
	{
		$count = new CountConnector($this->connector);
		$this->mockData('queryExists', false);
		self::assertEquals(false, $count->existsByField('a', 1));
	}
}