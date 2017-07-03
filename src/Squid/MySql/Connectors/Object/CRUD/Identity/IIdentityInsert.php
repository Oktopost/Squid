<?php
namespace Squid\MySql\Connectors\Object\CRUD\Identity;


interface IIdentityInsert
{
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function insert($object);
}