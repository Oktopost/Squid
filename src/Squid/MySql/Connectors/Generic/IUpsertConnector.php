<?php
namespace Squid\MySql\Connectors\Generic;


interface IUpsertConnector
{
	/**
	 * @param array $row
	 * @param string[]|string $keys
	 * @return int|false
	 */
	public function upsertByKeys(array $row, $keys);

	/**
	 * @param array $rows
	 * @param string[]|string $keys
	 * @return int|false
	 */
	public function upsertAllByKeys(array $rows, $keys);
	
	
	/**
	 * @param array $row
	 * @param string[]|string $valueFields
	 * @return int|false
	 */
	public function upsertByValues(array $row, $valueFields);

	/**
	 * @param array $rows
	 * @param string[]|string $valueFields
	 * @return int|false
	 */
	public function upsertAllByValues(array $rows, $valueFields);
}