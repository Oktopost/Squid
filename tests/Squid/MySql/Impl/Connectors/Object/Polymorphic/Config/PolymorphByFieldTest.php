<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic\Config;


use PHPUnit\Framework\TestCase;


class PolymorphByFieldTest extends TestCase
{
	public function test_ReturnSelf()
	{
		$subject = new PolymorphByFieldTestHelper();
		self::assertEquals($subject, $subject->addClass('a', 'b'));
		self::assertEquals($subject, $subject->addFieldRule('a', ['b' => 'c']));
	}
	
	
	public function test_addClass_PassArrayFirstTime()
	{
		$subject = new PolymorphByFieldTestHelper();
		$subject->addClass(['a'	=> 'conn1', 'b'	=> 'conn2']);
		
		self::assertEquals(['a'	=> 'conn1', 'b'	=> 'conn2'], $subject->callGetConnectorsByClass());
	}
	
	public function test_addClass_PassArrayNumberOfTimes()
	{
		$subject = new PolymorphByFieldTestHelper();
		$subject->addClass(['a'	=> 'conn1']);
		$subject->addClass(['b'	=> 'conn2']);
		
		self::assertEquals(['a'	=> 'conn1', 'b'	=> 'conn2'], $subject->callGetConnectorsByClass());
	}
	
	public function test_addClass_PassTwoParams()
	{
		$subject = new PolymorphByFieldTestHelper();
		$subject->addClass('a', 'conn1');
		
		self::assertEquals(['a'	=> 'conn1'], $subject->callGetConnectorsByClass());
	}
	
	public function test_addClass_PassTwoParamsWithExistingSetup()
	{
		$subject = new PolymorphByFieldTestHelper();
		$subject->addClass('a', 'conn1');
		$subject->addClass('b', 'conn2');
		
		self::assertEquals(['a'	=> 'conn1', 'b'	=> 'conn2'], $subject->callGetConnectorsByClass());
	}

	/**
	 * @expectedException \Squid\Exceptions\SquidDevelopmentException
	 */
	public function test_addClass_ClassNamePassedButConnecorIsNull_ThrowException()
	{
		$subject = new PolymorphByFieldTestHelper();
		$subject->addClass('a');
	}
	
	
	public function test_addFieldRule_PassArrayFirstTime()
	{
		$subject = new PolymorphByFieldTestHelper();
		$subject->addFieldRule(['a'	=> 'rule1', 'b'	=> 'rule2']);
		
		self::assertEquals(['a'	=> 'rule1', 'b'	=> 'rule2'], $subject->callGetByFieldRules());
	}
	
	public function test_addFieldRule_PassArrayNumberOfTimes()
	{
		$subject = new PolymorphByFieldTestHelper();
		$subject->addFieldRule(['a'	=> 'rule1']);
		$subject->addFieldRule(['b'	=> 'rule2']);
		
		self::assertEquals(['a'	=> 'rule1', 'b'	=> 'rule2'], $subject->callGetByFieldRules());
	}
	
	public function test_addFieldRule_PassTwoParams()
	{
		$subject = new PolymorphByFieldTestHelper();
		$subject->addFieldRule('a', 'rule1');
		
		self::assertEquals(['a'	=> 'rule1'], $subject->callGetByFieldRules());
	}
	
	public function test_addFieldRule_PassTwoParamsWithExistingSetup()
	{
		$subject = new PolymorphByFieldTestHelper();
		$subject->addFieldRule('a', 'rule1');
		$subject->addFieldRule('b', 'rule2');
		
		self::assertEquals(['a'	=> 'rule1', 'b'	=> 'rule2'], $subject->callGetByFieldRules());
	}

	/**
	 * @expectedException \Squid\Exceptions\SquidDevelopmentException
	 */
	public function test_addFieldRule_FieldPassedAsThingButWithoutRule_ThrowException()
	{
		$subject = new PolymorphByFieldTestHelper();
		$subject->addFieldRule('a');
	}
}


class PolymorphByFieldTestHelper extends PolymorphByField
{
	public function callGetByFieldRules(): array
	{
		return $this->getByFieldRules();
	}
	
	public function callGetConnectorsByClass(): array
	{
		return $this->getConnectorsByClass();
	}
}