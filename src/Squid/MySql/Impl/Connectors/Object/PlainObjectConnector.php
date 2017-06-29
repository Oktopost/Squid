<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Connectors\Object\IPlainObjectConnector;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


class PlainObjectConnector extends AbstractORMConnector implements IPlainObjectConnector
{
	private function cmdSelect(): ICmdSelect
	{
		return $this->getConnector()->select()->from($this->getTableName());
	}
	
	
	/**
	 * @param mixed|array $objects
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($objects, bool $ignore = false)
	{
		if (!is_array($objects))
			$objects = [$objects];
		
		return $this->getConnector()
			->insert()
			->into($this->getTableName())
			->valuesBulk($this->getObjectMap()->toRows($objects))
			->ignore($ignore)
			->executeDml(true);
	}

	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectObjectByFields(array $fields)
	{
		$result = $this->cmdSelect()
			->byFields($fields)
			->queryRow(true);
		
		return $this->getObjectMap()->toObject($result);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectObjectByField(string $field, $value)
	{
		$result = $this->cmdSelect()
			->byField($field, $value)
			->queryRow(true);
		
		return $this->getObjectMap()->toObject($result);
	}

	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByFields(array $fields)
	{
		$result = $this->cmdSelect()
			->byFields($fields)
			->limitBy(1)
			->queryRow(true, true);
		
		return $this->getObjectMap()->toObject($result);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByField(string $field, $value)
	{
		$result = $this->cmdSelect()
			->byField($field, $value)
			->limitBy(1)
			->queryRow(true, true);
		
		return $this->getObjectMap()->toObject($result);
	}

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return array|false
	 */
	public function selectObjectsByFields(array $fields, ?int $limit = null)
	{
		$result = $this->cmdSelect()
			->byFields($fields)
			->limitBy($limit)
			->queryAll(true);
		
		return $this->getObjectMap()->toObjects($result);
	}

	/**
	 * @param array|null $orderBy
	 * @return array|false
	 */
	public function selectObjects(?array $orderBy = null)
	{
		$result = $this->cmdSelect();
		
		if ($orderBy)
		{
			$result->orderBy($orderBy);
		}
		
		return $this->getObjectMap()->toObjects($result->queryAll(true));
	}

	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function updateObject($object, array $byFields)
	{
		return $this->getConnector()
			->update()
			->table($this->getTableName())
			->set($this->getObjectMap()->toRow($object))
			->byFields($byFields)
			->executeDml(true);
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertObjectsByKeys($objects, array $keys)
	{
		if (!is_array($objects))
			$objects = [$objects];
		
		return $this->getConnector()
			->upsert()
			->into($this->getTableName())
			->valuesBulk($this->getObjectMap()->toRows($objects))
			->setDuplicateKeys($keys)
			->executeDml(true);
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertObjectsByValues($objects, array $valueFields)
	{
		if (!is_array($objects))
			$objects = [$objects];
		
		return $this->getConnector()
			->upsert()
			->into($this->getTableName())
			->valuesBulk($this->getObjectMap()->toRows($objects))
			->setUseNewValues($valueFields)
			->executeDml(true);
	}
}