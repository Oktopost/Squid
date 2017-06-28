<?php
namespace Squid\MySql\Connectors\Object\CRUD\Generic;


interface IObjectUpdate
{
	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function updateByFields($object, array $byFields);
}