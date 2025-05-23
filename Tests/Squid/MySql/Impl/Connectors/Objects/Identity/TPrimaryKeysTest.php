<?php
namespace Squid\MySql\Impl\Connectors\Objects\Identity;


use PHPUnit\Framework\TestCase;


class TPrimaryKeysTest extends TestCase
{
	private static function assertKeysAre(array $expected, TPrimaryKeysTestHelper $subject)
	{
		self::assertEquals(array_values($expected), $subject->getPrimaryPropertiesMethod());
		self::assertEquals(array_keys($expected), $subject->getPrimaryFieldsMethod());

		self::assertEquals(count($expected), $subject->getKeysCountMethod());
		self::assertEquals($expected, $subject->getPrimaryKeysMethod());
	}


	public function test_SingleStringPassed()
	{
		$subject = new TPrimaryKeysTestHelper();
		$subject->setPrimaryKeys('a');

		self::assertKeysAre(['a' => 'a'], $subject);
	}

	public function test_NumericArrayPassed()
	{
		$subject = new TPrimaryKeysTestHelper();
		$subject->setPrimaryKeys(['a']);

		self::assertKeysAre(['a' => 'a'], $subject);
	}

	public function test_ArrayPassedWithOneKey()
	{
		$subject = new TPrimaryKeysTestHelper();
		$subject->setPrimaryKeys(['a' => 'b']);

		self::assertKeysAre(['a' => 'b'], $subject);
	}

	public function test_ArrayPassedWithNumberOfKeys()
	{
		$subject = new TPrimaryKeysTestHelper();
		$subject->setPrimaryKeys(['a' => 'b', 'c' => 'd']);

		self::assertKeysAre(['a' => 'b', 'c' => 'd'], $subject);
	}

	public function test_CalledMoreThenOnce_ExceptionThrown()
	{
		self::expectException(\Squid\Exceptions\SquidException::class);
		$subject = new TPrimaryKeysTestHelper();
		$subject->setPrimaryKeys('a');

		$subject->setPrimaryKeys('b');
	}

	public function test_EmptyArrayPassed_ExceptionThrown()
	{
		self::expectException(\Squid\Exceptions\SquidException::class);
		$subject = new TPrimaryKeysTestHelper();
		$subject->setPrimaryKeys([]);
	}

	public function test_EmptyStringPassed_ExceptionThrown()
	{
		self::expectException(\Squid\Exceptions\SquidException::class);
		$subject = new TPrimaryKeysTestHelper();
		$subject->setPrimaryKeys('');
	}
}


class TPrimaryKeysTestHelper
{
	use TPrimaryKeys;


	public function getPrimaryKeysMethod()
	{
		return $this->getPrimaryKeys();
	}

	public function getKeysCountMethod()
	{
		return $this->getKeysCount();
	}

	public function getPrimaryFieldsMethod()
	{
		return $this->getPrimaryFields();
	}

	public function getPrimaryPropertiesMethod()
	{
		return $this->getPrimaryProperties();
	}
}
