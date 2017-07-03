<?php
namespace Squid\MySql\Impl\Connectors\Object\Identity;


use lib\DataSet;
use lib\DummyObject;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;
use Squid\MySql\Impl\Connectors\Object\IdentityConnector;


class TIdentityDecoratorTest extends TestCase
{
	private static function subject(): TIdentityDecoratorTestHelper
	{
		$subject = new TIdentityDecoratorTestHelper();
		
		$subject->setObjectMap(DummyObject::class);
		$subject->setTable('a');
		$subject->setConnector(DataSet::connector());
		
		return $subject;
	}
	
	
	private function assertMethodCalled(string $name, ...$params): void
	{
		$subject = self::subject();
		$mock = $this->getMockBuilder(IdentityConnector::class)->getMock();
		$subject->object = $mock;
		
		$mock->expects(self::once())->method($name)->with(...$params)->willReturn(123);
		
		self::assertEquals(123, $subject->$name(...$params));
	}
	
	
	public function test_getIdentityConnector_SameObjectReturned()
	{
		$subject = self::subject();
		self::assertSame($subject->getIdentityConnectorMethod(), $subject->getIdentityConnectorMethod());
	}
	
	
	public function test_sanity()
	{
		$this->assertMethodCalled('delete', [new DummyObject()]);
		$this->assertMethodCalled('update', new DummyObject());
		$this->assertMethodCalled('upsert', [new DummyObject()]);
	}
}


class TIdentityDecoratorTestHelper extends AbstractORMConnector
{
	use TIdentityDecorator { TIdentityDecorator::getIdentityConnector as getIdentityConnectorOrigin; }
	
	
	public $object; 
	

	protected function getPrimaryKeys(): array
	{
		return ['a' => 'a'];
	}
	
	
	protected function getIdentityConnector()
	{
		if ($this->object)
			return $this->object;
		
		return $this->getIdentityConnectorOrigin();
	}
	
	
	public function getIdentityConnectorMethod()
	{
		return $this->getIdentityConnectorOrigin();
	}
}