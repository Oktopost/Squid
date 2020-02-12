<?php
namespace Squid\MySql\Connectors\Objects\CRUD\Identity;


interface IIdentityDelete
{
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object);
}