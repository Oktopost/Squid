<?php
namespace Squid\MySql\Connectors\Object\CRUD\Identity;


interface IIdentityUpsert
{
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object);
}