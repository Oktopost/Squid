<?php
namespace Squid\MySql\Connectors\Generic;


interface ISelectConnector
{
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return array|null|false
	 */
	public function oneByField(string $field, $value);

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return array|null|false
	 */
	public function firstByField(string $field, $value);

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return array|false
	 */
	public function allByField(string $field, $value);

	/**
	 * @param string $field
	 * @param mixed $value
	 * @param int $limit
	 * @return array|false
	 */
	public function nByField(string $field, $value, int $limit);
	
	/**
	 * @param array $fields
	 * @return array|null|false
	 */
	public function oneByFields(array $fields);

	/**
	 * @param array $fields
	 * @return array|null|false
	 */
	public function firstByFields(array $fields);

	/**
	 * @param array $fields
	 * @return array|false
	 */
	public function allByFields(array $fields);

	/**
	 * @param array $fields
	 * @param int $limit
	 * @return array|false
	 */
	public function nByFields(array $fields, int $limit);
	
	/**
	 * @return array|false
	 */
	public function all();
}