<?php
namespace Squid\MySql\Connectors\Object\CRUD;


interface IObjectDelete
{
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object);
}