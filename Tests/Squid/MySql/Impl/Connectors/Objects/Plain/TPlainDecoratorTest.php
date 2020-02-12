<?php
namespace Squid\MySql\Impl\Connectors\Objects\Plain;


use lib\DataSet;
use Objection\LiteObject;
use Objection\LiteSetup;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Impl\Connectors\Objects\PlainObjectConnector;
use Squid\MySql\Impl\Connectors\Internal\Objects\AbstractORMConnector;


class TPlainDecoratorTest extends TestCase
{
	private function subject()
	{
		return new TPlainDecoratorTestHelper();
	}
	
	private function decoratedClass(): string
	{
		return PlainObjectConnector::class;
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
		$subject = new TPlainDecoratorTestHelper();
		self::assertInstanceOf(PlainObjectConnector::class, $subject->get());
		self::assertSame($subject->get(), $subject->get());
	}
	
	public function test_Sanity()
	{
		$this->assertMethodCalled('insertObjects', new TPlainDecoratorTestHelperClass(), true);
		$this->assertMethodCalled('insertObjects', new TPlainDecoratorTestHelperClass(), false);
		
		$this->assertMethodCalled('selectObjectByField', 'a', 'b');
		$this->assertMethodCalled('selectObjectByFields', ['a' => 'b']);
		
		$this->assertMethodCalled('selectFirstObjectByField', 'a', 'b');
		$this->assertMethodCalled('selectFirstObjectByFields', ['a' => 'b']);
		
		$this->assertMethodCalled('selectObjectsByFields', ['a', 'b'], 1);
		$this->assertMethodCalled('selectObjectsByFields', ['a', 'b'], null);
		
		$this->assertMethodCalled('selectObjects', ['a' => 'b']);
		$this->assertMethodCalled('selectObjects', null);
		
		$this->assertMethodCalled('updateObject', new TPlainDecoratorTestHelperClass(), ['a' => 'b']);
		$this->assertMethodCalled('upsertObjectsByKeys', new TPlainDecoratorTestHelperClass(), ['a' => 'b']);
		$this->assertMethodCalled('upsertObjectsForValues', new TPlainDecoratorTestHelperClass(), ['a' => 'b']);
	}
}


class TPlainDecoratorTestHelperClass extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'a' => LiteSetup::createString('a')
		];
	}
}

class TPlainDecoratorTestHelper extends AbstractORMConnector
{
	use TPlainDecorator;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setTable('a');
		$this->setConnector(DataSet::connector());
		$this->setObjectMap(TPlainDecoratorTestHelperClass::class);
	}


	public function override($mock)
	{
		$this->_plainConnector = $mock;
	}
	
	public function get()
	{
		return $this->getPlainConnector();
	}
}