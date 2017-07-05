<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary\Insert;


use lib\DummyObject;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Connectors\Object\CRUD\Identity\IIdentityInsert;


class AbstractInsertHandlerTest extends TestCase
{
	public function test_ReturnSelf()
	{
		$subject = new AbstractInsertHandlerTestHelper();
		
		self::assertEquals($subject, $subject->setIdProperty('a'));
		self::assertEquals($subject, $subject->setInsertProvider(function() {}));
	}
	
	
	public function test_HandlerIsCallback_CallbackHandlerCalled()
	{
		$isCalled = false;
		
		$subject = new AbstractInsertHandlerTestHelper();
		$subject->setInsertProvider(function () use (&$isCalled) { $isCalled = true; });
		
		$subject->insert([new DummyObject()]);
		
		self::assertTrue($isCalled);
	}
	
	public function test_HandlerIsCallback_CallbackValueReturned()
	{
		$subject = new AbstractInsertHandlerTestHelper();
		$subject->setInsertProvider(function () { return 3; });
		
		self::assertEquals(3, $subject->insert([new DummyObject()]));
	}
	
	public function test_HandlerIsCallback_ParamsPassedToHandler()
	{
		$target = [new DummyObject(), new DummyObject()];
		
		$subject = new AbstractInsertHandlerTestHelper();
		$subject->setInsertProvider(function ($prms) use (&$params) { $params = $prms; });
		
		$subject->insert($target);
		
		self::assertSame($target, $params);
	}
	
	
	public function test_HandlerIsIIdentityInsert_CallbackValueReturned()
	{
		/** @var \PHPUnit_Framework_MockObject_MockObject|IIdentityInsert $mock */
		$mock = $this->createMock(IIdentityInsert::class);
		
		$subject = new AbstractInsertHandlerTestHelper();
		$subject->setInsertProvider($mock);
		
		$mock->method('insert')->willReturn(3);
		
		self::assertEquals(3, $subject->insert([new DummyObject()]));
	}
	
	public function test_HandlerIsIIdentityInsert_ParamsPassedToHandler()
	{
		$target = [new DummyObject(), new DummyObject()];
		
		/** @var \PHPUnit_Framework_MockObject_MockObject|IIdentityInsert $mock */
		$mock = $this->createMock(IIdentityInsert::class);
		
		$subject = new AbstractInsertHandlerTestHelper();
		$subject->setInsertProvider($mock);
		
		$mock->expects($this->once())->method('insert')->with($target);
		
		$subject->insert($target);
	}
}


class AbstractInsertHandlerTestHelper extends AbstractInsertHandler
{
	/**
	 * @param array $items
	 * @return int|false
	 */
	public function insert(array $items)
	{
		return $this->doInsert($items);
	}
}