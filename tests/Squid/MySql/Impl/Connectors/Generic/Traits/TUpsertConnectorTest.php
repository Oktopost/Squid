<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use lib\DataSet;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Impl\Connectors\Generic\UpsertConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class TUpsertConnectorTest extends TestCase
{
	private function subject()
	{
		return new TUpsertConnectorTestHelper();
	}
	
	private function decoratedClass(): string
	{
		return UpsertConnector::class;
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
		$subject = new TUpsertConnectorTestHelper();
		self::assertInstanceOf(UpsertConnector::class, $subject->get());
		self::assertSame($subject->get(), $subject->get());
	}
	
	public function test_Sanity()
	{
		$this->assertMethodCalled('upsertByKeys',		['a', 'b'], ['b', 'c']);
		$this->assertMethodCalled('upsertByKeys',		'c', ['a', 'b']);
		$this->assertMethodCalled('upsertAllByKeys',	['a', 'b'], ['b', 'c']);
		$this->assertMethodCalled('upsertAllByKeys',	'c', ['a', 'b']);
		$this->assertMethodCalled('upsertByValues',		['a', 'b'], ['b', 'c']);
		$this->assertMethodCalled('upsertByValues',		'c', ['a', 'b']);
		$this->assertMethodCalled('upsertAllByValues',	['a', 'b'], ['b', 'c']);
		$this->assertMethodCalled('upsertAllByValues',	'c', ['a', 'b']);
	}
}


class TUpsertConnectorTestHelper extends AbstractSingleTableConnector
{
	use TUpsertConnector { TUpsertConnector::getUpsertConnector as originGetUpsertConnector; }
	
	
	private $object;


	private function getUpsertConnector()
	{
		if (!$this->object)
			return $this->originGetUpsertConnector();
		
		return $this->object;
	}
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setTable('a');
		$this->setConnector(DataSet::connector());
	}


	public function override($mock)
	{
		$this->object = $mock;
	}
	
	public function get()
	{
		return $this->getUpsertConnector();
	}
}