<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use lib\DataSet;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Impl\Connectors\Generic\InsertConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class TInsertConnectorTest extends TestCase
{
	private function subject()
	{
		return new TInsertConnectorTestHelper();
	}
	
	private function decoratedClass(): string
	{
		return InsertConnector::class;
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
		$subject = new TInsertConnectorTestHelper();
		self::assertInstanceOf(InsertConnector::class, $subject->get());
		self::assertSame($subject->get(), $subject->get());
	}
	
	public function test_Sanity()
	{
		$this->assertMethodCalled('insertRow', ['a', 'b'], true);
		$this->assertMethodCalled('insertRow', ['a', 'b'], false);
		$this->assertMethodCalled('insertAll', ['a', 'b'], true);
		$this->assertMethodCalled('insertAll', ['a', 'b'], false);
		$this->assertMethodCalled('insertAllIntoFields', ['a', 'b'], ['c', 'd'], true);
		$this->assertMethodCalled('insertAllIntoFields', ['a', 'b'], ['c', 'd'], false);
	}
}


class TInsertConnectorTestHelper extends AbstractSingleTableConnector
{
	use TInsertConnector;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setTable('a');
		$this->setConnector(DataSet::connector());
	}


	public function override($mock)
	{
		$this->_insertConnector = $mock;
	}
	
	public function get()
	{
		return $this->getInsertConnector();
	}
}