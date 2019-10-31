<?php
namespace Squid\MySql\Connectors\Generic;


interface IDeleteConnector
{
	/**
	 * @param string $field
	 * @param mixed $value
	 * @param int|null $limit
	 * @return int|false
	 */
	public function deleteByField(string $field, $value, ?int $limit = null);

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return int|false
	 */
	public function deleteByFields(array $fields, ?int $limit = null);
}