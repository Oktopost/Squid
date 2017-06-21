<?php
namespace Squid\MySql\Connectors\Generic;


interface IInsertConnector
{
	/**
	 * @param array $row
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insert(array $row, bool $ignore = false);

	/**
	 * @param array $rows
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insertAll(array $rows, bool $ignore = false);

	/**
	 * @param array $fields
	 * @param array $rows
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insertAllIntoFields(array $fields, array $rows, bool $ignore = false);
}