<?php
namespace Squid\MySql\Connectors\Objects\CRUD\Generic;


interface IObjectUpdate
{
	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function updateObject($object, array $byFields);
}