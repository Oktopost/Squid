<?php
namespace Squid\MySql\Connectors\Generic;


interface IUpsertConnector
{
	/**
	 * @param string[]|string $keys
	 * @param array $row
	 * @return int|false
	 */
	public function upsertByKeys($keys, array $row);

	/**
	 * @param string[]|string $keys
	 * @param array $rows
	 * @return int|false
	 */
	public function upsertAllByKeys($keys, array $rows);
	
	
	/**
	 * @param string[]|string $valueFields
	 * @param array $row
	 * @return int|false
	 */
	public function upsertByValues($valueFields, array $row);

	/**
	 * @param string[]|string $valueFields
	 * @param array $rows
	 * @return int|false
	 */
	public function upsertAllByValues($valueFields, array $rows);
}