<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\IGenericCRUDConnector;
use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Connectors\Object\Selector\ICmdObjectSelect;
use Squid\MySql\Connectors\Object\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\TGenericObjectConnector;

use Squid\MySql\Impl\Connectors\GenericConnector;
use Squid\MySql\Impl\Connectors\Map\MapFactory;


class GenericObjectConnector extends ORMConnector implements IGenericObjectConnector
{
	use TGenericObjectConnector;
	
	
	/** @var IRowMap */
	private $mapper;
	
	/** @var IGenericCRUDConnector */
	private $genericCRUD;

	
	private function getGenericCRUD(): IGenericCRUDConnector
	{
		if (!$this->genericCRUD)
		{
			$this->genericCRUD = new GenericConnector($this);
		}
		
		return $this->genericCRUD;
	}
	
	
	/**
	 * @param mixed $mapper
	 */
	public function __construct($mapper)
	{
		parent::__construct();
		$this->mapper = MapFactory::create($mapper);
	}
	

	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insert($object, bool $ignore = false)
	{
		return $this->getGenericCRUD()->insert()->row($this->mapper->toRow($object), $ignore);
	}

	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function update($object, array $byFields)
	{		
		return $this->getGenericCRUD()->update()->byFields($byFields, $this->mapper->toRow($object));
	}

	/**
	 * @param mixed|array $object
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertByKeys($object, array $keys)
	{
		return $this->getGenericCRUD()->upsert()->byKeys($this->mapper->toRow($object), $keys);
	}

	/**
	 * @param mixed|array $object
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertValues($object, array $valueFields)
	{
		return $this->getGenericCRUD()->upsert()->byValues($this->mapper->toRow($object), $valueFields);
	}

	/**
	 * @return ICmdObjectSelect
	 */
	public function query(): ICmdObjectSelect
	{
		$query = new CmdObjectSelect($this->mapper);
		$query
			->setConnector($this->getConnector())
			->from($this->getTableName());
		
		return $query;
	}
}