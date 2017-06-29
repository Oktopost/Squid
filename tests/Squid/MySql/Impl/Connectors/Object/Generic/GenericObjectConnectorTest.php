<?php
namespace Squid\MySql\Impl\Connectors\Object\Generic;


use lib\DataSet;
use lib\TestTable;
use Objection\LiteObject;
use Objection\LiteSetup;
use Objection\Mapper;

use PHPUnit\Framework\TestCase;


class GenericObjectConnectorTest extends TestCase
{
	/** @var GenericObjectConnector */
	private $connector;
	
	
	private function newObject(): GenericObjectHelper
	{
		$result = new GenericObjectHelper();
		
		$result->a = mt_rand();
		$result->b = uniqid();
		
		return $result;
	}
	
	private function newTable(): TestTable
	{
		$t = DataSet::table(['a', 'b']);
		
		DataSet::connector()
			->direct("ALTER TABLE {$t} ADD PRIMARY KEY (a), CHANGE `a` `a` INT(11) NOT NULL AUTO_INCREMENT")
			->executeDml();
		
		return $t;
	}
	
	
	public function setUp()
	{
		$this->connector = new GenericObjectConnector();
		$this->connector
			->setConnector(DataSet::connector())
			->setObjectMap(Mapper::createFor(GenericObjectHelper::class))
			->setTable($this->newTable());
	}
	
	
	public function test_query_sanity()
	{
		$a = $this->newObject();
		$this->connector->insertObjects($a);
		
		$result = $this->connector->query()->byField('a', $a->a)->queryFirst();
		
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