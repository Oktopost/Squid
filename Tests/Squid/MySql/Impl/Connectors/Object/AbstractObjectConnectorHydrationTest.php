<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Objection\LiteObject;
use Objection\LiteSetup;
use PHPUnit\Framework\TestCase;
use Squid\Objects\AbstractObjectConnector;


/**
 * @property string $Id
 * @property string $Name
 */
class AbstractObjectConnectorHydrationObject extends LiteObject
{
	protected function _setup()
	{
		return [
			'Id'	=> LiteSetup::createString(),
			'Name'	=> LiteSetup::createString()
		];
	}
}


class AbstractObjectConnectorHydrationConnector extends AbstractObjectConnector
{
	public function hydrateOne(array $data)
	{
		return $this->createInstance($data);
	}
	
	public function hydrateAll(array $data)
	{
		return $this->createAllInstances($data);
	}
	
	public function insertAll(array $objects, array $excludeFields = [])
	{
		return 0;
	}
	
	public function loadOneByFields(array $byFields, array $orderFields = [])
	{
		return null;
	}
	
	public function loadFirstByFields(array $byFields, array $orderFields = [])
	{
		return null;
	}
	
	public function loadAllByFields(array $byFields, array $orderFields = [], $limit = 32)
	{
		return [];
	}
	
	public function updateByFields(array $set, array $byFields)
	{
		return 0;
	}
	
	public function upsertAll(array $objects, array $keyFields, array $excludeFields = [])
	{
		return false;
	}
	
	public function deleteByFields(array $fields)
	{
		return false;
	}
}


class AbstractObjectConnectorHydrationTest extends TestCase
{
	private function subject()
	{
		$connector = new AbstractObjectConnectorHydrationConnector();
		$connector->setDomain(AbstractObjectConnectorHydrationObject::class);
		
		return $connector;
	}
	
	public function test_createInstance_UnknownFieldInData_FieldIgnored()
	{
		$result = $this->subject()->hydrateOne([
			'Id'		=> 'id-1',
			'Name'		=> 'Name 1',
			'DbOnly'	=> 'ignored'
		]);
		
		self::assertInstanceOf(AbstractObjectConnectorHydrationObject::class, $result);
		self::assertEquals(['Id' => 'id-1', 'Name' => 'Name 1'], $result->toArray());
	}
	
	public function test_createAllInstances_UnknownFieldInData_FieldIgnored()
	{
		$result = $this->subject()->hydrateAll([
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
		self::assertEquals(
			[
				['Id' => 'id-1', 'Name' => 'Name 1'],
				['Id' => 'id-2', 'Name' => 'Name 2']
			],
			AbstractObjectConnectorHydrationObject::allToArray($result)
		);
	}
	
	public function test_fromArray_UnknownFieldInData_ErrorThrown()
	{
		$this->expectException(\Objection\Exceptions\PropertyNotFoundException::class);
		
		$object = new AbstractObjectConnectorHydrationObject();
		$object->fromArray(['DbOnly' => 'still strict']);
	}
}
