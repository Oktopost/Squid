<?php
namespace Squid\MySql\Connectors\Object\CRUD\Generic;


interface IObjectInsert
{
	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($object, bool $ignore = false);
}