<?php
namespace Squid\MySql\Impl\Connectors\Object\Generic;


use lib\DataSet;
use lib\TDBAssert;
use Objection\LiteObject;
use Objection\LiteSetup;
use Objection\Mapper;

use PHPUnit\Framework\TestCase;

use Squid\MySql\Connectors\Object\Query\ICmdObjectSelect;


class GenericObjectConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $table;
	
	
	private function createObject($a, $b): GenericObjectHelper
	{
		$result = new GenericObjectHelper();
		
		$result->a = $a;
		$result->b = $b;
		
		return $result;
	}
	
	private function subject()
	{
		$this->table = DataSet::table(['a', 'b']);
		
		$connector = new GenericObjectConnector();
		$connector
			->setConnector(DataSet::connector())
			->setObjectMap(Mapper::createFor(GenericObjectHelper::class))
			->setTable($this->table);
		
		return $connector;
	}
	
	
	public function test_sanity()
	{
		$subject = $this->subject();
		
		$a = $this->createObject(1, 2);
		
		$subject->insertObjects($a);
		self::assertRowExists($this->table, ['a' => 1, 'b' => 2]);
		
		$b = $subject->selectFirstObjectByFields(['b' => 2]);
		self::assertEquals($a->toArray(), $b->toArray());
		
		$subject->deleteByField('a', '1');
		self::assertRowCount(0, $this->table);
	}
	
	
	public function test_query_ICmdObjectSelectInstanceReturned()
	{
		self::assertInstanceOf(ICmdObjectSelect::class, $this->subject()->query());
	}
	
	public function test_query_sanity()
	{
		$subject = $this->subject();
		
		$a = $this->createObject(1, 2);
		$subject->insertObjects($a);
		$result = $subject->query()->byField('a', $a->a)->queryFirst();
		
		self::assertInstanceOf(GenericObjectHelper::class, $result);
	}
}


/**
 * @property string $a
 * @property string $b
 */
class GenericObjectHelper extends LiteObject
{
	protected function _setup()
	{
		return ['a' => LiteSetup::createInt(), 'b' => LiteSetup::createString()];
	}
}