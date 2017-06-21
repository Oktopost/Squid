<?php
namespace Squid\MySql\Connectors\Object\Generic;


interface IInsertObjectConnector
{
	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insert($object, bool $ignore = false);
}