<?php
namespace Squid\MySql\Connectors\Object\Generic;


interface IUpdateObjectConnector
{
	/**
	 * @param mixed $object
	 * @param array $byFields
	 * @return false|int
	 */
	public function update($object, array $byFields);
}