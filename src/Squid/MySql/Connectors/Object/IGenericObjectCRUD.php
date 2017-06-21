<?php
namespace Squid\MySql\Connectors\Object;


interface IGenericObjectCRUD
{
	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insert($object, bool $ignore = false);
	
	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function update($object, array $byFields);
	
	/**
	 * @param mixed|array $object
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertByKeys($object, array $keys);
	
	/**
	 * @param mixed|array $object
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertValues($object, array $valueFields);
	
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
	
	/**
	 * @return ICmdObjectSelect
	 */
	public function query(): ICmdObjectSelect;
}