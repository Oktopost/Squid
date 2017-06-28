<?php
namespace Squid\MySql\Connectors\Object\CRUD\Identity;


interface IIdentitySave
{
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function save($object);
}