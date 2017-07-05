<?php
namespace Squid\MySql\Connectors\Object\ID;


interface IIdGenerator
{
	/**
	 * Generate Ids for all given objects
	 * @param string $tableName
	 * @param array $objects
	 * @return string[]
	 */
	public function generate(string $tableName, array $objects): array;

	/**
	 * Called after the insert operation is preformed on the objects, both on success and failure.
	 * @param string[] $ids
	 */
	public function release(array $ids);
}