<?php
namespace Squid\MySql\Connectors\Object\CRUD\Generic;


interface IObjectInsert
{
	/**
	 * @param mixed|array $objects
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($objects, bool $ignore = false);
}