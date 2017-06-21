<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\IGenericConnector;
use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Connectors\IGenericCRUDConnector;
use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Connectors\Object\ICmdObjectSelect;
use Squid\MySql\Connectors\Object\IGenericObjectCRUD;
use Squid\MySql\Connectors\Object\TGenericObjectCRUD;

use Squid\MySql\Impl\Connectors\TGenericConnector;
use Squid\MySql\Impl\Connectors\GenericCRUDConnector;
use Squid\MySql\Impl\Connectors\TSingleTableConnector;
use Squid\MySql\Impl\Connectors\Map\MapFactory;

use Objection\Mapper;
use Objection\Mappers;


class GenericObjectCRUD implements IGenericConnector, IGenericObjectCRUD, ISingleTableConnector
{
	use TGenericConnector;
	use TGenericObjectCRUD;
	use TSingleTableConnector;
	
	
	/** @var IRowMap */
	private $mapper;
	
	/** @var IGenericCRUDConnector */
	private $genericCRUD;

	
	private function getGenericCRUD(): IGenericCRUDConnector
	{
		if (!$this->genericCRUD)
		{
			$this->genericCRUD = new GenericCRUDConnector();
			$this->genericCRUD
				->setConnector($this->getConnector())
				->setTable($this->getTable());
		}
		
		return $this->genericCRUD;
	}
	
	
	/**
	 * @param Mapper|IRowMap|string $mapper
	 */
	public function __construct($mapper)
	{
		if (is_string($mapper))
			$mapper = Mappers::simple()->setDefaultClassName($mapper);
		
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