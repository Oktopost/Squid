<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\IGenericCRUDConnector;
use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Connectors\Object\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\TGenericObjectConnector;
use Squid\MySql\Connectors\Object\Selector\ICmdObjectSelect;

use Squid\MySql\Impl\Connectors\Table\AbstractGenericConnector;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


class GenericObjectConnector extends AbstractORMConnector implements IGenericObjectConnector
{
	use TGenericObjectConnector;
	
	
	/** @var IRowMap */
	private $map;
	
	/** @var IGenericCRUDConnector */
	private $genericCRUD;

	
	private function getGenericCRUD(): IGenericCRUDConnector
	{
		if (!$this->genericCRUD)
		{
			$this->genericCRUD = new AbstractGenericConnector($this);
		}
		
		return $this->genericCRUD;
	}
	
	
	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insert($object, bool $ignore = false)
	{
		return $this->getGenericCRUD()->insert()->row($this->map->toRow($object), $ignore);
	}

	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function update($object, array $byFields)
	{		
		return $this->getGenericCRUD()->update()->byFields($byFields, $this->map->toRow($object));
	}

	/**
	 * @param mixed|array $object
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertByKeys($object, array $keys)
	{
		return $this->getGenericCRUD()->upsert()->byKeys($this->map->toRow($object), $keys);
	}

	/**
	 * @param mixed|array $object
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertValues($object, array $valueFields)
	{
		return $this->getGenericCRUD()->upsert()->byValues($this->map->toRow($object), $valueFields);
	}

	/**
	 * @return ICmdObjectSelect
	 */
	public function query(): ICmdObjectSelect
	{
		$query = new CmdObjectSelect($this->map);
		$query
			->setConnector($this->getConnector())
			->from($this->getTableName());
		
		return $query;
	}
}