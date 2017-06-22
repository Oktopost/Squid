<?php
namespace Squid\MySql\Impl\Connectors\Internal\Map\Maps;


use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Connectors\Map\TRowMap;


class ArrayMapper implements IRowMap
{
	use TRowMap;
	
	
	/** @var string[] Keys are database fields, values are target fields */
	private $map;
	
	
	public function __construct(array $map)
	{
		$this->map = $map;
	}


	/**
	 * @param mixed $object
	 * @return array Assoc array that can be inserted into the database.
	 */
	public function toRow($object): array
	{
		$row = [];
		
		foreach ($this->map as $db => $source)
		{
			$row[$db] = $object[$source];
		}
		
		return $row;
	}

	/**
	 * @param array $row Assoc row from database.
	 * @return mixed
	 */
	public function toObject(array $row)
	{
		$object = [];
		
		foreach ($this->map as $db => $source)
		{
			$object[$source] = $row[$db];
		}
		
		return $object;
	}
}