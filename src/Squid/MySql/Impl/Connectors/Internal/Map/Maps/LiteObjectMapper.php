<?php
namespace Squid\MySql\Impl\Connectors\Internal\Map\Maps;


use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Connectors\Map\TRowMap;

use Objection\Mapper;


class LiteObjectMapper implements IRowMap
{
	use TRowMap;
	
	
	/** @var Mapper */
	private $mapper;
	
	/** @var array */
	private $exclude;
	
	
	public function __construct(Mapper $mapper, array $excludeFields)
	{
		$this->mapper = $mapper;
		$this->exclude = $excludeFields;
	}
	
	
	/**
	 * @param mixed $object
	 * @return array Assoc array that can be inserted into the database.
	 */
	public function toRow($object): array
	{
		$result = $this->mapper->getArray($object);
		
		foreach ($this->exclude as $key)
		{
			if (array_key_exists($key, $result))
			{
				unset($result[$key]);
			}
		}
		
		return $result;
	}
	
	/**
	 * @param array $row Assoc row from database.
	 * @return mixed
	 */
	public function toObject(array $row)
	{
		return $this->mapper->getObject($row);
	}
}