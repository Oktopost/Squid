<?php
namespace Squid\MySql\Connectors\Object\CRUD;


interface IObjectUpdate
{
	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object);
}