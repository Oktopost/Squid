<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use lib\DataSet;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Impl\Connectors\Generic\CountConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class TCountConnectorTest extends TestCase
{
	private function subject()
	{
		return new TCountConnectorTestHelper();
	}

	private function decoratedClass(): string
	{
		return CountConnector::class;
	}


	private function assertMethodCalled(string $name, ...$params): void
	{
		$subject = $this->subject();
		$mock = self::createMock($this->decoratedClass());
		$subject->override($mock);

		$returnValue = (strpos($name, 'exists') === 0) ? true : 123;

		$mock->expects($this->once())->method($name)->with(...$params)->willReturn($returnValue);

		self::assertEquals($returnValue, $subject->$name(...$params));
	}


	public function test_SameConnectorUsed()
	{
		$subject = new TCountConnectorTestHelper();
		self::assertInstanceOf(CountConnector::class, $subject->get());
		self::assertSame($subject->get(), $subject->get());
	}

	public function test_count_Sanity()
	{
		$this->assertMethodCalled('countByField', 'a', 'b');
		$this->assertMethodCalled('countByFields', ['a', 'b']);
	}

	public function test_exists_Sanity()
	{
		$this->assertMethodCalled('existsByField', 'a', 'b');
		$this->assertMethodCalled('existsByFields', ['a', 'b']);
	}
}


class TCountConnectorTestHelper extends AbstractSingleTableConnector
{
	use TCountConnector { TCountConnector::getCountConnector as originGetCountConnector; }


	private $object;


	private function getCountConnector()
	{
		if (!$this->object)
			return $this->originGetCountConnector();

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
		return $this->getCountConnector();
	}
}
