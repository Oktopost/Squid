<?php
namespace Squid\MySql\Impl\Connectors\Internal\Map\Maps;


use Squid\MySql\Connectors\Map\IRowMap;

use Objection\LiteObject;


class LiteObjectSimpleMapper implements IRowMap
{
	/** @var string|LiteObject */
	private $className;
	
	/** @var array */
	private $exclude;
	
	
	public function __construct($className, array $excludeFields)
	{
		$this->className = $className;
		$this->exclude = $excludeFields;
	}


	/**
	 * @param mixed|LiteObject $object
	 * @return array Assoc array that can be inserted into the database.
	 */
	public function toRow($object): array
	{
		return $object->toArray([], $this->exclude);
	}

	/**
	 * @param array $objects
	 * @return array Array of rows.
	 */
	public function toRows(array $objects): array
	{
		return  $this->className::allToArray($objects, [], $this->exclude);
	}
	
	/**
	 * @param array $row Assoc row from database.
	 * @return mixed
	 */
	public function toObject(array $row)
	{
		$className = $this->className;
		
		/** @var LiteObject $object */
		$object = new $className;
		
		return $object->fromArray($row);
	}
	
	/**
	 * @param array $rows Array of rows from database.
	 * @return array Array of objects
	 */
	public function toObjects(array $rows): array
	{
		return $this->className::allFromArray($rows);
	}
}