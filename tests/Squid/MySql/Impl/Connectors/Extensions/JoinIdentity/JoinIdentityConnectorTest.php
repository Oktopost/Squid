<?php
namespace Squid\MySql\Impl\Connectors\Extensions\JoinIdentity;


use lib\DataSet;
use lib\TDBAssert;

use Objection\LiteObject;
use Objection\LiteSetup;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Impl\Connectors\Extensions\JoinIdentity\Config\JoinConfigByFields;
use Squid\MySql\Impl\Connectors\Object\SimpleObjectConnector;


class JoinIdentityConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	// Table A has elements with id < 100
	private $tableA;
	
	// Table B has elements with id > 100 and < 10000
	private $tableB;
	
	
	private function insertAObjects($data)
	{
		if ($data && !is_array($data[0]))
			$data = [$data];
		
		return DataSet::table(['ID', 'Name'], $data);
	}
	
	private function insertBObjects($data)
	{
		if ($data && !is_array($data[0]))
			$data = [$data];
		
		return DataSet::table(['ID', 'RefID', 'Data'], $data);
	}
	
	private function subject(array $dataA = [], array $dataB = [], $autoInc = false)
	{
		$this->tableA = $this->insertAObjects($dataA);
		$this->tableB = $this->insertBObjects($dataB);
		$config = new JoinConfigByFields('ID', 'RefID', 'Prop');
		
		DataSet::connector()->direct("ALTER TABLE {$this->tableA} ADD PRIMARY KEY (ID), CHANGE `ID` `ID` INT(11) NOT NULL AUTO_INCREMENT")->executeDml();
		DataSet::connector()->direct("ALTER TABLE {$this->tableB} ADD PRIMARY KEY (ID), CHANGE `ID` `ID` INT(11) NOT NULL AUTO_INCREMENT")->executeDml();
		
		$join = new JoinIdentityConnector($config);
		$join->setObjectConnector(
				(new SimpleObjectConnector())
					->setConnector(DataSet::connector())
					->setAutoincrementID('ID')
					->setObjectMap(JoinHelper_A::class)
			)
			->setDataConnector(
				(new SimpleObjectConnector())
					->setConnector(DataSet::connector())
					->setAutoincrementID('ID')
					->setObjectMap(JoinHelper_B::class)
			);
	}
}


class JoinHelper_A extends LiteObject
{
	public function __construct($data = [])
	{
		parent::__construct();
		if ($data) $this->fromArray($data);
	}

	protected function _setup()
	{
		return [
			'ID'	=> LiteSetup::createInt(null),
			'Name'	=> LiteSetup::createString(0),
			'Prop'	=> LiteSetup::createInstanceOf(JoinHelper_B::class)
		];
	}
}

class JoinHelper_B extends LiteObject
{
	public function __construct($data = [])
	{
		parent::__construct();
		if ($data) $this->fromArray($data);
	}
	
	protected function _setup()
	{
		return [
			'ID'	=> LiteSetup::createInt(null),
			'RefID'	=> LiteSetup::createInt(null),
			'Data'	=> LiteSetup::createString(0)
		];
	}
}