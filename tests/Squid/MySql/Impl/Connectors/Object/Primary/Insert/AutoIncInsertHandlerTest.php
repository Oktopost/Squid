<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary\Insert;


use lib\DataSet;
use lib\DummyObject;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Impl\Connectors\Object\IdentityConnector;


class AutoIncInsertHandlerTest extends TestCase
{
	public function subject(): AutoIncInsertHandler
	{
		$table = DataSet::table(['a', 'b']);
		
		DataSet::connector()
			->direct("ALTER TABLE {$table} ADD PRIMARY KEY (a), CHANGE `a` `a` INT(11) NOT NULL AUTO_INCREMENT")->executeDml();
		
		$conn = new IdentityConnector();
		$conn
			->setConnector(DataSet::connector())
			->setTable($table)
			->setObjectMap(DummyObject::class)
			->setPrimaryKeys('a');
		
		$subject = new AutoIncInsertHandler();
		$subject->setInsertProvider($conn);
		$subject->setConnector(DataSet::connector());
		$subject->setIdProperty('a');
		
		return $subject;
	}

	
	public function test_ReturnSelf()
	{
		$subject = $this->subject();
		self::assertEquals($subject, $subject->setConnector(DataSet::connector()));
	}
	
	
	public function test_SingleArrayPassed()
	{
		$obj1 = new DummyObject(['a' => null, 'b' => 1]);
		
		$res = $this->subject()->insert([$obj1]);
		
		self::assertEquals(1, $res);
		self::assertEquals(1, $obj1->a);
	}
	
	public function test_NumberOfElementsPassed()
	{
		$obj1 = new DummyObject(['a' => null, 'b' => 1]);
		$obj2 = new DummyObject(['a' => null, 'b' => 2]);
		$obj3 = new DummyObject(['a' => null, 'b' => 2]);
		
		$res = $this->subject()->insert([$obj1, $obj2, $obj3]);
		
		self::assertEquals(3, $res);
		
		self::assertEquals(1, $obj1->a);
		self::assertEquals(2, $obj2->a);
		self::assertEquals(3, $obj3->a);
	}
}