<?php
namespace Squid\MySql\Connectors\Object\CRUD\Generic;


interface IObjectSelect
{
	/**
	 * @param array $fields
	 * @return mixed|false
	 */
	public function selectObjectByFields(array $fields);
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|false
	 */
	public function selectObjectByField(string $field, $value);
	
	/**
	 * @param array $fields
	 * @return mixed|false
	 */
	public function selectFirstObjectByFields(array $fields);
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|false
	 */
	public function selectFirstObjectByField(string $field, $value);

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return array|false
	 */
	public function selectObjectsByFields(array $fields, ?int $limit = null);
	
	/**
	 * @param array|null $orderBy
	 * @return array|false
	 */
	public function selectObjects(?array $orderBy = null);
}