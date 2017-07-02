<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use lib\DataSet;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Impl\Connectors\Generic\DeleteConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class TDeleteConnectorTest extends TestCase
{
	private function subject()
	{
		return new TDeleteConnectorTestHelper();
	}
	
	private function decoratedClass(): string
	{
		return DeleteConnector::class;
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
		$subject = new TDeleteConnectorTestHelper();
		self::assertInstanceOf(DeleteConnector::class, $subject->get());
		self::assertSame($subject->get(), $subject->get());
	}
	
	public function test_Sanity()
	{
		$this->assertMethodCalled('deleteByField', 'a', 'b', 2);
		$this->assertMethodCalled('deleteByField', 'a', 'b', null);
		$this->assertMethodCalled('deleteByFields', ['a', 'b'], 2);
		$this->assertMethodCalled('deleteByFields', ['a', 'b'], null);
	}
}


class TDeleteConnectorTestHelper extends AbstractSingleTableConnector
{
	use TDeleteConnector;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setTable('a');
		$this->setConnector(DataSet::connector());
	}


	public function override($mock)
	{
		$this->_deleteConnector = $mock;
	}
	
	public function get()
	{
		return $this->getDeleteConnector();
	}
}