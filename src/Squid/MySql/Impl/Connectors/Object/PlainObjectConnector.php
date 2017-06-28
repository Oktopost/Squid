<?php

namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\Object\IPlainObjectConnector;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


class PlainObjectConnector extends AbstractORMConnector implements IPlainObjectConnector
{
	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insert($object, bool $ignore = false)
	{
		// TODO: Implement insert() method.
	}

	/**
	 * @param array $fields
	 * @return mixed|false
	 */
	public function selectOneByFields(array $fields)
	{
		// TODO: Implement selectOneByFields() method.
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|false
	 */
	public function selectOneByField(string $field, $value)
	{
		// TODO: Implement selectOneByField() method.
	}

	/**
	 * @param array $fields
	 * @return mixed|false
	 */
	public function selectFirstByFields(array $fields)
	{
		// TODO: Implement selectFirstByFields() method.
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|false
	 */
	public function selectFirstByField(string $field, $value)
	{
		// TODO: Implement selectFirstByField() method.
	}

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return array|false
	 */
	public function selectAllByFields(array $fields, ?int $limit = null)
	{
		// TODO: Implement selectAllByFields() method.
	}

	/**
	 * @param array|null $orderBy
	 * @return array|false
	 */
	public function selectAll(?array $orderBy = null)
	{
		// TODO: Implement selectAll() method.
	}

	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function updateByFields($object, array $byFields)
	{
		// TODO: Implement update() method.
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertByKeys($objects, array $keys)
	{
		// TODO: Implement upsertByKeys() method.
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertValues($objects, array $valueFields)
	{
		// TODO: Implement upsertValues() method.
	}
}