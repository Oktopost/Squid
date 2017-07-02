<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use lib\DataSet;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Impl\Connectors\Generic\SelectConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class TSelectConnectorTest extends TestCase
{
	private function subject()
	{
		return new TSelectConnectorTestHelper();
	}
	
	private function decoratedClass(): string
	{
		return SelectConnector::class;
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
		$subject = new TSelectConnectorTestHelper();
		self::assertInstanceOf(SelectConnector::class, $subject->get());
		self::assertSame($subject->get(), $subject->get());
	}
	
	public function test_Sanity()
	{
		$this->assertMethodCalled('oneByField',		'a', 'b');
		$this->assertMethodCalled('firstByField',	'a', 'b');
		$this->assertMethodCalled('allByField',		'a', 'b');
		$this->assertMethodCalled('nByField',		'a', 'b', 3);
		
		$this->assertMethodCalled('oneByFields',	['a', 'b']);
		$this->assertMethodCalled('firstByFields',	['a', 'b']);
		$this->assertMethodCalled('allByFields',	['a', 'b']);
		$this->assertMethodCalled('nByFields',		['a', 'b'], 10);
		
		$this->assertMethodCalled('all');
	}
}


class TSelectConnectorTestHelper extends AbstractSingleTableConnector
{
	use TSelectConnector;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setTable('a');
		$this->setConnector(DataSet::connector());
	}


	public function override($mock)
	{
		$this->_selectConnector = $mock;
	}
	
	public function get()
	{
		return $this->getSelectConnector();
	}
}