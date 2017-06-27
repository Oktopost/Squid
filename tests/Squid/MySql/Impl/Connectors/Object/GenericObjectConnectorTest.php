<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Exceptions\MySqlException;
use Squid\MySql\Connectors\Object\IGenericObjectConnector;

use Objection\LiteSetup;
use Objection\LiteObject;

use lib\DataSet;
use lib\TestTable;

use PHPUnit\Framework\TestCase;


class GenericObjectConnectorTest extends TestCase
{
	/** @var IGenericObjectConnector */
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
		$this->connector->setConnector(DataSet::connector());
		$this->connector->setObjectMap(GenericObjectHelper::class);
		$this->connector->setTable($this->newTable());
	}
	
	
	public function test_insert_OneObject_ReturnInt()
	{
		self::assertEquals(1, $this->connector->insert($this->newObject()));
	}
	
	public function test_insert_MultipleObjects_ReturnInt()
	{
		$objects = [$this->newObject(), $this->newObject(), $this->newObject()];
		self::assertEquals(3, $this->connector->insert($objects));
	}
	
	public function test_insert_Ignore_ReturnInt()
	{
		$a = $this->newObject();
		self::assertEquals(1, $this->connector->insert([$a, $a], true));
	}
	
	public function test_insert_Ignore_ReturnZero()
	{
		$a = $this->newObject();
		$this->connector->insert($a);
		
		self::assertEquals(0, $this->connector->insert($a, true));
	}
	
	public function test_insert_Duplicate_ThrowException()
	{
		self::expectException(MySqlException::class);
		
		$a = $this->newObject();
		$this->connector->insert([$a, $a], false);
	}
	
	public function test_update_ReturnInt()
	{
		$a = $this->newObject();
		$this->connector->insert($a);
		$a->b = 'oi';
		self::assertEquals(1, $this->connector->update($a, ['a']));
	}
	
	public function test_update_ReturnZero()
	{
		$a = $this->newObject();
		self::assertEquals(0, $this->connector->update($a, ['a']));
	}
	
	public function test_update_NoChanges_ReturnZero()
	{
		$a = $this->newObject();
		$this->connector->insert($a);
		
		self::assertEquals(0, $this->connector->update($a, ['a']));
	}
	
	public function test_upsertByKeys_newObject_ReturnInt()
	{
		self::assertEquals(1, $this->connector->upsertByKeys($this->newObject(), ['a']));
	}
	
	public function test_upsertByKeys_newObjects_ReturnInt()
	{
		$a = $this->newObject();
		$b = $this->newObject();
		
		self::assertEquals(2, $this->connector->upsertByKeys([$a, $b], ['a']));
	}
	
	public function test_upsertByKeys_oldObjects_ReturnInt()
	{
		$a = $this->newObject();
		$b = $this->newObject();
		
		$this->connector->insert([$a, $b]);
		$a->a = 'qwerty';
		
		self::assertEquals(1, $this->connector->upsertByKeys([$a, $b], ['a']));
	}
	
	public function test_upsertValues_OneObject_ReturnInt()
	{
		$a = $this->newObject();
		$this->connector->insert($a);
		
		$b = $this->newObject();
		$b->a = $a->a;
		
		self::assertEquals(2, $this->connector->upsertValues($b, ['b']));
	}
	
	public function test_upsertValues_MultipleObjects_ReturnInt()
	{
		$a = $this->newObject();
		$b = $this->newObject();
		
		$this->connector->insert([$a, $b]);
		
		$c = $this->newObject();
		$d = $this->newObject();
		
		$c->a = $a->a;
		
		self::assertEquals(3, $this->connector->upsertValues([$c, $d], ['b']));
	}
	
	public function test_query_sanity()
	{
		$a = $this->newObject();
		$this->connector->insert($a);
		
		$result = $this->connector->query()->byField('a', $a->a)->queryFirst();
		
		self::assertInstanceOf(LiteObject::class, $result);
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