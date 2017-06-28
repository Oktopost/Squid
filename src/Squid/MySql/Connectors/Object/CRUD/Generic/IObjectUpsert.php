<?php
namespace Squid\MySql\Connectors\Object\CRUD\Generic;


interface IObjectUpsert
{
	/**
	 * @param mixed|array $objects
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertByKeys($objects, array $keys);
	
	/**
	 * @param mixed|array $objects
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertValues($objects, array $valueFields);
}