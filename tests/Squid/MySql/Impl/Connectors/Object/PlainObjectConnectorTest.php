<?php
namespace Squid\MySql\Impl\Connectors\Object;


use lib\DataSet;
use lib\TestTable;
use Objection\LiteObject;
use Objection\LiteSetup;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Exceptions\MySqlException;


class PlainObjectConnectorTest extends TestCase
{
	/** @var PlainObjectConnector */
	private $connector;
	
	
	private function newObject(): PlainObjectHelper
	{
		$result = new PlainObjectHelper();
		
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
		$this->connector = new PlainObjectConnector();
		$this->connector->setConnector(DataSet::connector());
		$this->connector->setObjectMap(PlainObjectHelper::class);
		$this->connector->setTable($this->newTable());
	}
	
	
	public function test_insertObjects_ReturnInt()
	{
		self::assertEquals(1, $this->connector->insertObjects($this->newObject()));
	}
	
	public function test_insertObjects_Multiple_ReturnInt()
	{
		self::assertEquals(2, $this->connector->insertObjects([$this->newObject(), $this->newObject()]));
	}
	
	public function test_insertObjects_Ignore_ReturnInt()
	{
		$a = $this->newObject();
		self::assertEquals(1, $this->connector->insertObjects([$a, $a], true));
	}
	
	public function test_insert_Ignore_ReturnZero()
	{
		$a = $this->newObject();
		$this->connector->insertObjects($a);
		
		self::assertEquals(0, $this->connector->insertObjects($a, true));
	}
	
	public function test_insert_Duplicate_ThrowException()
	{
		self::expectException(MySqlException::class);
		
		$a = $this->newObject();
		$this->connector->insertObjects([$a, $a], false);
	}
	
	
	public function test_selectObjectByFields_ReturnObject()
	{
		$a = $this->newObject();
		$this->connector->insertObjects($a);
		
		self::assertInstanceOf(PlainObjectHelper::class, $this->connector->selectObjectByFields(['a' => $a->a]));
	}
	
	public function test_selectObjectByFields_ReturnNull()
	{
		self::assertNull($this->connector->selectObjectByFields(['a' => -1]));
	}
	
	public function test_selectObjectByField_ReturnObject()
	{
		$a = $this->newObject();
		$this->connector->insertObjects($a);
		
		self::assertInstanceOf(PlainObjectHelper::class, $this->connector->selectObjectByField('a', $a->a));
	}
	
	public function test_selectObjectByField_ReturnNull()
	{
		self::assertNull($this->connector->selectObjectByField('a', -1));
	}
	
	public function test_selectFirstObjectByFields_ReturnObject()
	{
		$a = $this->newObject();
		$this->connector->insertObjects([$a, $this->newObject()]);
		
		self::assertInstanceOf(PlainObjectHelper::class, $this->connector->selectFirstObjectByFields(['a' => $a->a]));
	}
	
	public function test_selectFirstObjectByFields_ReturnNull()
	{
		self::assertNull($this->connector->selectFirstObjectByFields(['a' => -1]));
	}
	
	public function test_selectFirstObjectByField_ReturnObject()
	{
		$a = $this->newObject();
		$this->connector->insertObjects($a);
		
		self::assertInstanceOf(PlainObjectHelper::class, $this->connector->selectFirstObjectByField('a', $a->a));
	}
	
	public function test_selectFirstObjectByField_ReturnNull()
	{
		self::assertNull($this->connector->selectFirstObjectByField('a', -1));
	}
	
	public function test_selectObjectsByFields_ReturnObjects()
	{
		$a = $this->newObject();
		$b = $this->newObject();
		
		$a->b = 'string';
		$b->b = 'string';
		
		$this->connector->insertObjects([$a, $b]);
		
		$result = $this->connector->selectObjectsByFields(['b' => 'string']);
		
		self::assertCount(2, $result);
		self::assertInstanceOf(PlainObjectHelper::class, $result[0]);
	}
	
	public function test_selectObjectsByFields_ReturnNull()
	{
		self::assertNull($this->connector->selectObjectsByFields(['a' => -1]));	
	}
	
	public function test_selectObjects_ReturnObjects()
	{
		$this->connector->insertObjects([$this->newObject(), $this->newObject()]);
		
		$result = $this->connector->selectObjects();
		
		self::assertCount(2, $result);
		self::assertInstanceOf(PlainObjectHelper::class, $result[0]);
	}
	
	public function test_updateObject_ReturnInt()
	{
		$a = $this->newObject();
		$this->connector->insertObjects($a);
		$a->b = 'oi';
		
		self::assertEquals(1, $this->connector->updateObject($a, ['a']));
	}
	
	public function test_updateObject_ReturnZero()
	{
		self::assertEquals(0, $this->connector->updateObject($this->newObject(), ['a']));
	}
	
	public function test_upsertObjectByKeys_ReturnInt()
	{
		$a = $this->newObject();
		$b = $this->newObject();
		$c = $this->newObject();
		
		$c->a = $b->a;
		
		self::assertEquals(4, $this->connector->upsertObjectsByKeys([$a,$b,$c], ['a']));
	}
	
	public function test_upsertObjectsByValues_ReturnInt()
	{
		$a = $this->newObject();
		$b = $this->newObject();
		$c = $this->newObject();
		
		$c->a = $b->a;
		
		self::assertEquals(4, $this->connector->upsertObjectsByValues([$a,$b,$c], ['b']));
	}
}

/**
 * @property string $a
 * @property string $b
 */
class PlainObjectHelper extends LiteObject
{
	protected function _setup()
	{
		return ['a' => LiteSetup::createInt(), 'b' => LiteSetup::createString()];
	}
}