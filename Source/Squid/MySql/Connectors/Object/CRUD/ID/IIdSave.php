<?php
namespace Squid\MySql\Connectors\Object\CRUD\ID;


interface IIdSave
{
	/**
	 * @param mixed|array $objects
	 * @return int|false
	 */
	public function save($objects);
}