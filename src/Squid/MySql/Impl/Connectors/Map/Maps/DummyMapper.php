<?php
namespace Squid\MySql\Impl\Connectors\Map\Maps;


use Squid\MySql\Connectors\Map\IRowMap;


class DummyMapper implements IRowMap
{
	/**
	 * @param mixed $object
	 * @return array Assoc array that can be inserted into the database.
	 */
	public function toRow($object): array
	{
		return $object;
	}

	/**
	 * @param array $row Assoc row from database.
	 * @return mixed
	 */
	public function toObject(array $row)
	{
		return $row;
	}

	/**
	 * @param array $objects
	 * @return array Array of rows.
	 */
	public function toRows(array $objects): array
	{
		return $objects;
	}

	/**
	 * @param array $rows Array of rows from database.
	 * @return array Array of objects
	 */
	public function toObjects(array $rows): array
	{
		return $rows;
	}
}