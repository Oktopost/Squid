<?php
namespace Squid\MySql\Impl\Connectors\Objects\Primary\Insert;


use lib\DataSet;
use lib\DummyObject;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Connectors\Objects\ID\IIdGenerator;
use Squid\MySql\Impl\Connectors\Objects\IdentityConnector;


class GeneratedIdInsertHandlerTest extends TestCase
{
	/** @var GeneratedIdInsertHandlerTestHelper */
	private $generator;
	
	private $table;
	
	
	public function subject(): GeneratedIdInsertHandler
	{
		$table = DataSet::table(['a', 'b']);
		$this->table = (string)$table;
		
		DataSet::connector()->direct("ALTER TABLE {$table} ADD PRIMARY KEY (a)")->executeDml();
		
		$conn = new IdentityConnector();
		$conn
			->setConnector(DataSet::connector())
			->setTable($table)
			->setObjectMap(DummyObject::class)
			->setPrimaryKeys('a');
		
		$this->generator = new GeneratedIdInsertHandlerTestHelper();
		
		$subject = new GeneratedIdInsertHandler();
		return $subject->setInsertProvider($conn)
			->setGenerator($this->generator)
			->setIdProperty('a')
			->setTableName($table);
	}

	
	public function test_ReturnSelf()
	{
		$subject = $this->subject();
		self::assertEquals($subject, $subject->setGenerator(new GeneratedIdInsertHandlerTestHelper()));
		self::assertEquals($subject, $subject->setTableName('a'));
	}
	
	
	public function test_SingleArrayPassed()
	{
		$obj1 = new DummyObject(['a' => null, 'b' => 1]);
		
		$subject = $this->subject();
		$this->generator->ids = ['a', 'b', 'c'];
		
		$res = $subject->insert([$obj1]);
		
		self::assertEquals(1, $res);
		self::assertEquals('a', $obj1->a);
		self::assertSame([$this->table, [$obj1]], $this->generator->generate);
		self::assertSame(['a'], $this->generator->release);
	}
	
	public function test_NumberOfElementsPassed()
	{
		$obj1 = new DummyObject(['a' => null, 'b' => 1]);
		$obj2 = new DummyObject(['a' => null, 'b' => 2]);
		$obj3 = new DummyObject(['a' => null, 'b' => 3]);

		$subject = $this->subject();
		$this->generator->ids = ['a', 'b', 'c', 'd'];
		
		$res = $subject->insert([$obj1, $obj2, $obj3]);
		
		self::assertEquals(3, $res);
		self::assertEquals('a', $obj1->a);
		self::assertEquals('b', $obj2->a);
		self::assertEquals('c', $obj3->a);
		
		self::assertSame([$this->table, [$obj1, $obj2, $obj3]], $this->generator->generate);
		self::assertSame(['a', 'b', 'c'], $this->generator->release);
	}
}


class GeneratedIdInsertHandlerTestHelper implements IIdGenerator
{
	public $ids			= null;
	public $generate	= null;
	public $release		= null; 

	/**
	 * Generate Ids for all given objects
	 * @param string $tableName
	 * @param array $objects
	 * @return string[]
	 */
	public function generate(string $tableName, array $objects): array
	{
		$this->generate = [$tableName, $objects];
		return array_slice($this->ids, 0, count($objects));
	}

	/**
	 * Called after the insert operation is preformed on the objects, both on success and failure.
	 * @param string[] $ids
	 */
	public function release(array $ids)
	{
		$this->release = $ids;
	}
}