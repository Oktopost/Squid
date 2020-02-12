<?php
namespace Squid\MySql\Impl\Connectors\Objects\Primary;


use lib\DataSet;
use lib\DummyObject;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Connectors\Objects\IIdConnector;
use Squid\MySql\Connectors\Objects\ID\IIdGenerator;

use Squid\MySql\Impl\Connectors\Objects\IdConnector;
use Squid\MySql\Impl\Connectors\Internal\Objects\AbstractORMConnector;


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
	
	
	public function test_setAutoIncrementId_ConnectorCalled()
	{
		$mock = $this->createMock(IdConnector::class);
		$subject = $this->subject();
		$subject->object = $mock;
		
		$mock->expects($this->once())->method('setAutoIncrementId')->with('a')->willReturnSelf();
		
		$res = $subject->setAutoIncrementId('a');
		
		self::assertEquals($subject, $res);
	}
	
	public function test_setAutoIncrementId_Sanity()
	{
		$subject = $this->subject();
		$subject->setAutoIncrementId('a');
		self::assertInstanceOf(IIdConnector::class, $subject->getIdConnectorMethod());
	}
	
	
	public function test_setGeneratedId_ConnectorCalled()
	{
		$mock = $this->createMock(IdConnector::class);
		$subject = $this->subject();
		$subject->object = $mock;
		
		/** @var IIdGenerator $generator */
		$generator = $this->createMock(IIdGenerator::class);
		$mock->expects($this->once())->method('setGeneratedId')->with('a', $generator)->willReturnSelf();
		
		$res = $subject->setGeneratedId('a', $generator);
		
		self::assertEquals($subject, $res);
	}
	
	public function test_setGeneratedId_Sanity()
	{
		/** @var IIdGenerator $generator */
		$generator = $this->createMock(IIdGenerator::class);
		
		$subject = $this->subject();
		$subject->setGeneratedId('a', $generator);
		self::assertInstanceOf(IIdConnector::class, $subject->getIdConnectorMethod());
	}
}


class TIdDecoratorTestHelper extends AbstractORMConnector
{
	use TIdDecorator { 
		TIdDecorator::getIdConnector as getIdConnectorOrigin;
		TIdDecorator::getBareIdConnector as getBareIdConnectorOrigin; 
	}
	
	
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
	
	protected function getBareIdConnector()
	{
		if ($this->object)
			return $this->object;
		
		return $this->getBareIdConnectorOrigin();
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