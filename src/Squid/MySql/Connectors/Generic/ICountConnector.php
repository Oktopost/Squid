<?php
namespace Squid\MySql\Connectors\Generic;


interface ICountConnector
{
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return int|false
	 */
	public function byField(string $field, $value);

	/**
	 * @param array $fields
	 * @return int|false
	 */
	public function byFields($fields);

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return bool
	 */
	public function existsByField(string $field, $value): bool;

	/**
	 * @param array $fields
	 * @return bool
	 */
	public function existsByFields($fields): bool;
}