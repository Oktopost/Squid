<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Objection\LiteObject;
use Objection\LiteSetup;

use lib\DataSet;
use lib\TestTable;

use PHPUnit\Framework\TestCase;
use Squid\Exceptions\SquidException;
use Squid\MySql\Connectors\Object\ID\IIDGenerator;


class SimpleObjectConnectorTest extends TestCase
{
	/** @var SimpleObjectConnector */
	private $connector;
	
	
	private function newObject(): SimpleObjectHelper
	{
		$object = new SimpleObjectHelper();
		
		$object->b = uniqid();
		
		return $object;
	}
	
	private function newTable(): TestTable
	{
		$t = DataSet::table(['a', 'b']);
		
		DataSet::connector()
			->direct("ALTER TABLE {$t} ADD PRIMARY KEY (a), CHANGE `a` `a` INT(11) NOT NULL AUTO_INCREMENT")
			->executeDml();
		
		return $t;
	}
	
	private function setAutoId(): void
	{
		$this->connector->setAutoincrementID('a');
	}
	
	private function setManualId(): void
	{
		$this->connector->setIDProperty('a');
		$this->connector->setIDGenerator(new class implements IIDGenerator {
			
			public function generate(string $tableName, array $objects): array
			{
				$result = [];
				
				foreach ($objects as $key=>$value)
				{
					$result[] = $key;
				}
				
				return $result;
			}
			
			public function release(array $ids)
			{
				//
			}
		});
	}
	
	
	public function setUp()
	{
		$generic = new GenericObjectConnector();
		$generic->setConnector(DataSet::connector());
		$generic->setObjectMap(SimpleObjectHelper::class);
		$generic->setTable($this->newTable());
		$this->connector = new SimpleObjectConnector($generic);
	}
	
	
	public function test_insert_ReturnInt()
	{
		$this->setManualId();
		$object = $this->newObject();
		self::assertEquals(1, $this->connector->insert($object));
	}
	
	public function test_insert_AutoIncrement_ReturnInt()
	{
		$this->setAutoId();
		
		$object = $this->newObject();
		
		self::assertEquals(1, $this->connector->insert($object));
		self::assertEquals(1, $object->a);
	}
	
	public function test_insert_WithoutMapper_ThrowsException()
	{
		self::expectException(SquidException::class);
		
		$this->connector->insert($this->newObject());
	}
}


/**
 * @property string $a
 * @property string $b
 */
class SimpleObjectHelper extends LiteObject
{
	protected function _setup()
	{
		return ['a' => LiteSetup::createInt(), 'b' => LiteSetup::createString()];
	}
}