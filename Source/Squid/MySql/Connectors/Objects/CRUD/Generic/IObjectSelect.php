<?php
namespace Squid\MySql\Connectors\Objects\CRUD\Generic;


use Squid\OrderBy;


interface IObjectSelect
{
	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectObjectByFields(array $fields);
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectObjectByField(string $field, $value);
	
	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByFields(array $fields);
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
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
	 * @param int $order
	 * @return array|false
	 */
	public function selectObjects(?array $orderBy = null, int $order = OrderBy::DESC);
}