<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\OneToOne;


use Squid\MySql\Connectors\IGenericConnector;
use Squid\MySql\Connectors\Object\Join\OneToOne\IOneToOneConnector;


class OneToOneConnector implements IOneToOneConnector
{
	/** @var IGenericConnector */
	private $primaryConnector;
	
	
	public function countByField(string $field, $value) { return $this->primaryConnector->countByField($field, $value); }
	public function countByFields(array $fields) { return $this->primaryConnector->countByFields($fields); }
	public function existsByField(string $field, $value): bool { return $this->primaryConnector->existsByField($field, $value); }
	public function existsByFields(array $fields): bool { return $this->primaryConnector->existsByFields($fields); }
	public function deleteByField(string $field, $value, ?int $limit = null) { return $this->primaryConnector->deleteByField($field, $value, $limit); }
	public function deleteByFields(array $fields, ?int $limit = null) { return $this->primaryConnector->deleteByFields($fields, $limit); }
	
	
	/**
	 * @param mixed|array $objects
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($objects, bool $ignore = false)
	{
		// TODO: Implement insertObjects() method.
	}

	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectObjectByFields(array $fields)
	{
		// TODO: Implement selectObjectByFields() method.
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectObjectByField(string $field, $value)
	{
		// TODO: Implement selectObjectByField() method.
	}

	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByFields(array $fields)
	{
		// TODO: Implement selectFirstObjectByFields() method.
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByField(string $field, $value)
	{
		// TODO: Implement selectFirstObjectByField() method.
	}

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return array|false
	 */
	public function selectObjectsByFields(array $fields, ?int $limit = null)
	{
		// TODO: Implement selectObjectsByFields() method.
	}

	/**
	 * @param array|null $orderBy
	 * @return array|false
	 */
	public function selectObjects(?array $orderBy = null)
	{
		// TODO: Implement selectObjects() method.
	}

	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function updateObject($object, array $byFields)
	{
		// TODO: Implement updateObject() method.
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertObjectsByKeys($objects, array $keys)
	{
		// TODO: Implement upsertObjectsByKeys() method.
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertObjectsByValues($objects, array $valueFields)
	{
		// TODO: Implement upsertObjectsByValues() method.
	}
}