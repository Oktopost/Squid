<?php
namespace Squid\MySql\Connectors\Objects\CRUD\Identity;


interface IIdentityUpdate
{
	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object);
}