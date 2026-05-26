<?php
namespace Squid\MySql\Impl\Connectors\Internal\Map\Maps;


use Objection\Exceptions\PropertyNotFoundException;
use Objection\LiteObject;
use Objection\LiteSetup;
use PHPUnit\Framework\TestCase;


/**
 * @property string $Id
 * @property string $Name
 */
class LiteObjectSimpleMapperTestObject extends LiteObject
{
	protected function _setup()
	{
		return [
			'Id'	=> LiteSetup::createString(),
			'Name'	=> LiteSetup::createString()
		];
	}
}


/**
 * @property string $Name
 * @property array $Payload
 */
class LiteObjectSimpleMapperTestObjectWithOverride extends LiteObject
{
	protected function _setup()
	{
		return [
			'Name'		=> LiteSetup::createString(),
			'Payload'	=> LiteSetup::createArray()
		];
	}
	
	public function fromArray($source, $ignoreGetOnly = true)
	{
		if (isset($source['Payload']) && is_string($source['Payload']))
		{
			$source['Payload'] = json_decode($source['Payload'], true);
		}
		
		return parent::fromArray($source, $ignoreGetOnly);
	}
}


class LiteObjectSimpleMapperTest extends TestCase
{
	public function test_toObject_UnknownFieldInData_FieldIgnored()
	{
		$mapper = new LiteObjectSimpleMapper(LiteObjectSimpleMapperTestObject::class, []);
		
		$result = $mapper->toObject([
			'Id'		=> 'id-1',
			'Name'		=> 'Name 1',
			'DbOnly'	=> 'ignored'
		]);
		
		self::assertInstanceOf(LiteObjectSimpleMapperTestObject::class, $result);
		self::assertSame('id-1', $result->Id);
		self::assertSame('Name 1', $result->Name);
	}
	
	public function test_toObjects_UnknownFieldInData_FieldIgnored()
	{
		$mapper = new LiteObjectSimpleMapper(LiteObjectSimpleMapperTestObject::class, []);
		
		$result = $mapper->toObjects([
			[
				'Id'		=> 'id-1',
				'Name'		=> 'Name 1',
				'DbOnly'	=> 'ignored'
			],
			[
				'Id'			=> 'id-2',
				'Name'			=> 'Name 2',
				'AnotherDbOnly'	=> 'ignored'
			]
		]);
		
		self::assertCount(2, $result);
		self::assertSame('id-1', $result[0]->Id);
		self::assertSame('Name 1', $result[0]->Name);
		self::assertSame('id-2', $result[1]->Id);
		self::assertSame('Name 2', $result[1]->Name);
	}
	
	public function test_fromArrayFiltered_UnknownFieldInData_FieldIgnoredAndOverrideCalled()
	{
		$object = new LiteObjectSimpleMapperTestObjectWithOverride();
		
		$object->fromArrayFiltered([
			'Name'		=> 'Name 1',
			'Payload'	=> '{"key":"value"}',
			'DbOnly'	=> 'ignored'
		]);
		
		self::assertSame('Name 1', $object->Name);
		self::assertSame(['key' => 'value'], $object->Payload);
	}
	
	public function test_fromArray_UnknownFieldInData_ErrorThrown()
	{
		$this->expectException(PropertyNotFoundException::class);
		
		$object = new LiteObjectSimpleMapperTestObject();
		$object->fromArray(['DbOnly' => 'still strict']);
	}
}
