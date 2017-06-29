<?php
namespace Squid\MySql\Impl\Connectors\Internal\Generic;


use Squid\MySql\Command\ICmdDelete;
use Squid\MySql\Connectors\Table\ITableNameConnector;
use Squid\MySql\Impl\Connectors\Generic\DeleteConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;

use PHPUnit\Framework\TestCase;


class DeleteConnectorTest extends TestCase
{
	/** @var ICmdDelete|\PHPUnit_Framework_MockObject_MockObject */
	private $delete;
	
	/** @var AbstractSingleTableConnector|\PHPUnit_Framework_MockObject_MockObject */
	private $connector;
	
	
	private function mockDelete()
	{
		$this->delete = $this->getMockBuilder(ICmdDelete::class)->getMock();
	}
	
	private function mockAbstractTableConnector()
	{
		$tableConnector = $this->getMockBuilder(ITableNameConnector::class)->getMock();
		$tableConnector->method('delete')->willReturn($this->delete);
		
		$this->connector = $this->getMockBuilder(AbstractSingleTableConnector::class)->getMock();
		$this->connector->method('getTable')->willReturn($tableConnector);
	}
	
	private function mockData($result, $limit = null)
	{
		$this->delete
			->expects($this->once())
			->method('byFields')
			->with(['a' => 1])
			->willReturnSelf();
		
		if ($limit)
		{
			$this->delete
				->expects($this->once())
				->method('limitBy')
				->willReturnSelf();
		}
		
		$this->delete
			->expects($this->once())
			->method('executeDml')
			->willReturn($result);
	}
	
	
	public function setUp()
	{
		$this->mockDelete();
		$this->mockAbstractTableConnector();
	}
	
	
	public function test_byFields_NoLimit()
	{
		$deleteConnector = new DeleteConnector($this->connector);
		$this->mockData(2);
		
		self::assertEquals(2, $deleteConnector->deleteByFields(['a' => 1]));
	}
	
	public function test_byFields_WithLimit()
	{
		$deleteConnector = new DeleteConnector($this->connector);
		$this->mockData(2, 2);
		self::assertEquals(2, $deleteConnector->deleteByFields(['a' => 1], 2));
	}
	
	
	public function test_byField_NoLimit()
	{
		$deleteConnector = new DeleteConnector($this->connector);
		$this->mockData(2);
		self::assertEquals(2, $deleteConnector->deleteByField('a', 1));
	}
	
	public function test_byField_WithLimit()
	{
		$deleteConnector = new DeleteConnector($this->connector);
		$this->mockData(2, 2);
		self::assertEquals(2, $deleteConnector->deleteByField('a', 1, 2));
	}
}