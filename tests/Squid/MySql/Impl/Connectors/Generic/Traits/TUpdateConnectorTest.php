<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use lib\DataSet;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Impl\Connectors\Generic\UpdateConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class TUpdateConnectorTest extends TestCase
{
	private function subject()
	{
		return new TUpdateConnectorTestHelper();
	}
	
	private function decoratedClass(): string
	{
		return UpdateConnector::class;
	}
	
	
	private function assertMethodCalled(string $name, ...$params): void
	{
		$subject = $this->subject();
		$mock = self::createMock($this->decoratedClass());
		$subject->override($mock);
		
		$mock->expects($this->once())->method($name)->with(...$params)->willReturn(123);
		
		self::assertEquals(123, $subject->$name(...$params));
	}
	
	
	public function test_SameConnectorUsed()
	{
		$subject = new TUpdateConnectorTestHelper();
		self::assertInstanceOf(UpdateConnector::class, $subject->get());
		self::assertSame($subject->get(), $subject->get());
	}
	
	public function test_Sanity()
	{
		$this->assertMethodCalled('updateByRowFields',	['a', 'b'], ['b', 'c']);
		$this->assertMethodCalled('updateByFields',		['a', 'b'], ['b', 'c']);
	}
}


class TUpdateConnectorTestHelper extends AbstractSingleTableConnector
{
	use TUpdateConnector;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setTable('a');
		$this->setConnector(DataSet::connector());
	}


	public function override($mock)
	{
		$this->_updateConnector = $mock;
	}
	
	public function get()
	{
		return $this->getUpdateConnector();
	}
}