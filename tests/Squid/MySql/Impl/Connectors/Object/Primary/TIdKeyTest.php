<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary;


use PHPUnit\Framework\TestCase;


class TIdKeyTest extends TestCase
{
	private static function assertKeyIs(array $expected, TIdKeyTestHelper $subject)
	{
		$key = key($expected);
		
		self::assertEquals($key, $subject->getIdFieldMethod());
		self::assertEquals($expected[$key], $subject->getIdPropertyMethod());
		
		self::assertEquals($expected, $subject->getIdKeyMethod());
	}
	
	
	public function test_SingleStringPassed()
	{
		$subject = new TIdKeyTestHelper();
		$subject->setIdKey('a');
		
		self::assertKeyIs(['a' => 'a'], $subject);
	}
	
	public function test_PropertyAndFieldPassed()
	{
		$subject = new TIdKeyTestHelper();
		$subject->setIdKey('a', 'b');
		
		self::assertKeyIs(['a' => 'b'], $subject);
	}
	
	public function test_ArrayPassed()
	{
		$subject = new TIdKeyTestHelper();
		$subject->setIdKey(['a' => 'b']);
		
		self::assertKeyIs(['a' => 'b'], $subject);
	}

	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_CalledMoreThenOnce_ExceptionThrown()
	{
		$subject = new TIdKeyTestHelper();
		$subject->setIdKey('a');
		
		$subject->setIdKey('b');
	}

	/**
	 * @expectedException \Squid\Exceptions\SquidException
	 */
	public function test_MoreThenOneColumnInArrayPassed_ExceptionThrown()
	{
		$subject = new TIdKeyTestHelper();
		$subject->setIdKey(['a' => 'b', 'c' => 'd']);
		
		$subject->setIdKey('b');
	}
}


class TIdKeyTestHelper
{
	use TIdKey;
	
	
	public function getIdFieldMethod()
	{
		return $this->getIdField();
	}
	
	public function getIdPropertyMethod()
	{
		return $this->getIdProperty();
	}
	
	public function getIdKeyMethod()
	{
		return $this->getIdKey();
	}
}