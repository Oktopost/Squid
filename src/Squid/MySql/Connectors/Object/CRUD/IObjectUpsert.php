<?php
namespace Squid\MySql\Connectors\Object\CRUD;


interface IObjectUpsert
{
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object);
}