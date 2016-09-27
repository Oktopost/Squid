<?php
namespace Squid\KeyValue;


class KeyValueProviderTest extends \PHPUnit_Framework_TestCase
{
	/** @var IKeyValueConnector|\PHPUnit_Framework_MockObject_MockObject */
	private $connector;
	
	
	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|IKeyValueConnector
	 */
	private function getConnector()
	{
		$this->connector = $this->getMock(IKeyValueConnector::class);
		return $this->connector;
	}
	
	/**
	 * @return KeyValueProvider
	 */
	private function getTestSubject()
	{
		return new KeyValueProvider($this->getConnector());
	}
	
	/**
	 * @return KeyValueProvider
	 */
	private function getTestSubjectWithNotCachedObject()
	{
		$provider = $this->getTestSubject();
		
		$this->connector
			->method('get')
			->willReturn(null);
		
		return $provider;
	}
	
	
	public function test_get_GetOnConnectorCalled()
	{
		$obj = $this->getTestSubject();
		
		$this->connector
			->expects($this->once())
			->method('get')
			->with('a')
			->willReturn('1');
		
		$obj->get('a');
	}
	
	public function test_get_ObjectReturnedByConnectorIsReturned()
	{
		$obj = $this->getTestSubject();
		
		$this->connector
			->expects($this->once())
			->method('get')
			->willReturn('1');
		
		$this->assertEquals('1', $obj->get('a'));
	}
	
	public function test_get_ObjectNotFound_CallbackCalled()
	{
		$obj = $this->getTestSubjectWithNotCachedObject();
		
		$isCalled = false;
		$a = function()	use (&$isCalled) { $isCalled = true; };
		
		$obj->get('a', $a);
		
		$this->assertTrue($isCalled);
	}
	
	public function test_get_CallbackNotPassed_DefaultCallbackCalled()
	{
		$obj = $this->getTestSubjectWithNotCachedObject();
		
		$isCalled = false;
		
		$obj->setDefaultCallback(function()	use (&$isCalled) { $isCalled = true; });
		$obj->get('a');
		
		$this->assertTrue($isCalled);
	}
	
	public function test_get_CallbackPassedAndDefaultSet_DefaultNotCalled()
	{
		$obj = $this->getTestSubjectWithNotCachedObject();
		
		$obj->setDefaultCallback(function()	{ $this->fail(); });
		$obj->get('a', function() {});
	}
	
	/**
	 * @expectedException \Exception
	 */
	public function test_get_ObjectNotFoundAndCallbacksNotProvided_ThrowException()
	{
		$obj = $this->getTestSubjectWithNotCachedObject();
		$obj->get('a');
	}
	
	public function test_get_ObjectNotFound_KeyPassedToCallback()
	{
		$obj = $this->getTestSubjectWithNotCachedObject();
		$obj->get('a', function ($key) { $this->assertEquals('a', $key); });
	}
	
	public function test_get_ObjectNotFoundAndCallbackHaveOne_ObjectStored()
	{
		$obj = $this->getTestSubjectWithNotCachedObject();
		
		$this->connector
			->expects($this->once())
			->method('set')
			->with('a', '1');
		
		$obj->setDefaultCallback(function() { return '1'; });
		$obj->get('a');
	}
	
	public function test_get_ObjectNotFoundAndCallbackReturnsNull_setNotCalled()
	{
		$obj = $this->getTestSubjectWithNotCachedObject();
		
		$this->connector
			->expects($this->never())
			->method('set');
		
		$obj->setDefaultCallback(function() { return null; });
		$obj->get('a');
	}
}