<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\IGenericCRUDConnector;
use Squid\MySql\Connectors\Object\IPlainObjectConnector;
use Squid\MySql\Connectors\Object\TGenericObjectConnector;
use Squid\MySql\Connectors\Object\Query\ICmdObjectSelect;

use Squid\MySql\Impl\Connectors\Object\Query\CmdObjectSelect;
use Squid\MySql\Impl\Connectors\Table\GenericConnector;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


/**
 * @deprecated 
 */
class ObjectConnector extends AbstractORMConnector implements IPlainObjectConnector
{
	use TGenericObjectConnector;
	
	
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
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insert($object, bool $ignore = false)
	{
		if (!is_array($object))
			$object = [$object];
		
		return $this->getGenericCRUD()->insert()->all($this->getObjectMap()->toRows($object), $ignore);
	}

	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return int|false
	 */
	public function updateByFields($object, array $byFields)
	{		
		return $this->getGenericCRUD()->update()->byFields($byFields, $this->getObjectMap()->toRow($object));
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $keys
	 * @return int|false
	 */
	public function upsertByKeys($objects, array $keys)
	{
		if (!is_array($objects))
			$objects = [$objects];
		
		return $this->getGenericCRUD()->upsert()->allByKeys($this->getObjectMap()->toRows($objects), $keys);
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $valueFields
	 * @return int|false
	 */
	public function upsertValues($objects, array $valueFields)
	{
		if (!is_array($objects))
			$objects = [$objects];
		
		return $this->getGenericCRUD()->upsert()->allByValues($this->getObjectMap()->toRows($objects), $valueFields);
	}

	/**
	 * @return ICmdObjectSelect
	 */
	public function query(): ICmdObjectSelect
	{
		$query = new CmdObjectSelect($this->getObjectMap());
		$query
			->setConnector($this->getConnector())
			->from($this->getTableName());
		
		return $query;
	}
}