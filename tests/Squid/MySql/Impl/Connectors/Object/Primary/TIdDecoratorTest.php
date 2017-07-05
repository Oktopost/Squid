<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary;


use lib\DataSet;
use lib\DummyObject;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;
use Squid\MySql\Impl\Connectors\Object\IdConnector;


class TIdDecoratorTest extends TestCase
{
	private function subject(): TIdDecoratorTestHelper
	{
		$subject = new TIdDecoratorTestHelper();
		
		$subject->setObjectMap(DummyObject::class);
		$subject->setTable('a');
		$subject->setConnector(DataSet::connector());
		
		return $subject;
	}
	
	
	public function test_getIdConnector_SameObjectReturned()
	{
		$subject = $this->subject();
		self::assertSame($subject->getIdConnectorMethod(), $subject->getIdConnectorMethod());
	}
	
	
	public function test_deleteById_ConnectorCalled()
	{
		$mock = $this->createMock(IdConnector::class);
		$subject = $this->subject();
		$subject->object = $mock;
		
		$mock->expects($this->once())->method('deleteById')->with('a')->willReturn(12);
		
		$res = $subject->deleteById('a');
		
		self::assertEquals(12, $res);
	}
	
	
	public function test_loadById_ConnectorCalled()
	{
		$mock = $this->createMock(IdConnector::class);
		$subject = $this->subject();
		$subject->object = $mock;
		
		$mock->expects($this->once())->method('loadById')->with('a')->willReturn(12);
		
		$res = $subject->loadById('a');
		
		self::assertEquals(12, $res);
	}
	
	
	public function test_save_ConnectorCalled()
	{
		$mock = $this->createMock(IdConnector::class);
		$subject = $this->subject();
		$subject->object = $mock;
		
		$mock->expects($this->once())->method('save')->with('a')->willReturn(12);
		
		$res = $subject->save('a');
		
		self::assertEquals(12, $res);
	}
}


class TIdDecoratorTestHelper extends AbstractORMConnector
{
	use TIdDecorator { TIdDecorator::getIdConnector as getIdConnectorOrigin; }
	
	
	public $object; 
	
	
	protected function getIdKey(): array
	{
		return ['a' => 'a'];
	}
	
	protected function getIdConnector()
	{
		if ($this->object)
			return $this->object;
		
		return $this->getIdConnectorOrigin();
	}
	
	
	public function getIdConnectorMethod()
	{
		return $this->getIdConnectorOrigin();
	}

	protected function getIdProperty(): string
	{
		return 'a';
	}
}