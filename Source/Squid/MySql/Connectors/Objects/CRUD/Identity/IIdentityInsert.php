<?php
namespace Squid\MySql\Connectors\Objects\CRUD\Identity;


interface IIdentityInsert
{
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function insert($object);
}