<?php
namespace Squid\MySql\Connectors\Object\CRUD\Generic;


interface IObjectUpsert
{
	/**
	 * @param mixed|array $objects
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertObjectsByKeys($objects, array $keys);
	
	/**
	 * @param mixed|array $objects
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertObjectsByValues($objects, array $valueFields);
}