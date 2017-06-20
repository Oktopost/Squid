<?php
namespace Squid\MySql\Connectors\Map;


interface IRowMap
{
	/**
	 * @param mixed $object
	 * @return array Assoc array that can be inserted into the database.
	 */
	public function toRow($object): array;

	/**
	 * @param array $objects
	 * @return array Array of rows.
	 */
	public function toRows(array $objects): array;

	/**
	 * @param array $row Assoc row from database.
	 * @return mixed
	 */
	public function toObject(array $row);

	/**
	 * @param array $rows Array of rows from database.
	 * @return array Array of objects
	 */
	public function toObjects(array $rows): array;
}