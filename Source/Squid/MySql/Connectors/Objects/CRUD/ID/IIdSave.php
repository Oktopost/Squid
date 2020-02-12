<?php
namespace Squid\MySql\Connectors\Objects\CRUD\ID;


interface IIdSave
{
	/**
	 * @param mixed|array $objects
	 * @return int|false
	 */
	public function save($objects);
}