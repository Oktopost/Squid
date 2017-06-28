<?php
namespace Squid\MySql\Connectors\Object\CRUD\Generic;


interface IObjectSelect
{
	/**
	 * @param array $fields
	 * @return mixed|false
	 */
	public function selectOneByFields(array $fields);
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|false
	 */
	public function selectOneByField(string $field, $value);
	
	/**
	 * @param array $fields
	 * @return mixed|false
	 */
	public function selectFirstByFields(array $fields);
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|false
	 */
	public function selectFirstByField(string $field, $value);

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return array|false
	 */
	public function selectAllByFields(array $fields, ?int $limit = null);
	
	/**
	 * @param array|null $orderBy
	 * @return array|false
	 */
	public function selectAll(?array $orderBy = null);
}