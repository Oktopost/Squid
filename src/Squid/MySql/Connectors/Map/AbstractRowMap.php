<?php
namespace Squid\MySql\Connectors\Map;


abstract class AbstractRowMap implements IRowMap
{
	/**
	 * @param array $objects
	 * @return array Array of rows.
	 */
	public function toRows(array $objects): array
	{
		$rows = [];
		
		foreach ($objects as $object)
		{
			$rows[] = $this->toRow($object);
		}
		
		return $rows;
	}
	
	/**
	 * @param array $rows Array of rows from database.
	 * @return array Array of objects
	 */
	public function toObjects(array $rows): array
	{
		$objects = [];
		
		foreach ($rows as $row)
		{
			$objects[] = $this->toObject($row);
		}
		
		return $objects;
	}
}