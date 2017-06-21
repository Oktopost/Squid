<?php
namespace Squid\MySql\Connectors\Generic;


interface IUpsertConnector
{
	/**
	 * @param array $row
	 * @param string[]|string $keys
	 * @return int|false
	 */
	public function byKeys(array $row, $keys);

	/**
	 * @param array $rows
	 * @param string[]|string $keys
	 * @return int|false
	 */
	public function allByKeys(array $rows, $keys);
	
	
	/**
	 * @param array $row
	 * @param string[]|string $valueFields
	 * @return int|false
	 */
	public function byValues(array $row, $valueFields);

	/**
	 * @param array $rows
	 * @param string[]|string $valueFields
	 * @return int|false
	 */
	public function allByValues(array $rows, $valueFields);
}