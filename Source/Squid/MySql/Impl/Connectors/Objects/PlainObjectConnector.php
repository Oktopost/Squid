<?php
namespace Squid\MySql\Impl\Connectors\Objects;


use Squid\OrderBy;

use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Connectors\Objects\IPlainObjectConnector;
use Squid\MySql\Impl\Connectors\Internal\Objects\AbstractORMConnector;

use Squid\Exceptions\SquidException;


class PlainObjectConnector extends AbstractORMConnector implements IPlainObjectConnector
{
	private function getSelect(): ICmdSelect
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
		{
			$objects = [$objects];
		}
		
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
		$object = null;
		$res = $this->getSelect()
			->byFields($fields)
			->queryWithCallback(
				function($row)
					use (&$object)
				{
					if ($object)
						throw new SquidException('More then one row selected!');
					
					$object = $this->getObjectMap()->toObject($row);
				});
		
		return $res ? $object : null;
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectObjectByField(string $field, $value)
	{
		return $this->selectObjectByFields([$field => $value]);
	}
	
	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByFields(array $fields)
	{
		$object = null;
		$res = $this->getSelect()
			->byFields($fields)
			->queryWithCallback(
				function($row)
					use (&$object)
				{
					$object = $this->getObjectMap()->toObject($row);
					return 0;
				});
		
		return $res ? $object : null;
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByField(string $field, $value)
	{
		return $this->selectFirstObjectByFields([$field => $value]);
	}
	
	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return array|false
	 */
	public function selectObjectsByFields(array $fields, ?int $limit = null)
	{
		$query = $this->getSelect()->byFields($fields);
		
		if ($limit)
			$query->limitBy($limit);
		
		$result = $query->queryAll(true);
		
		return $result ? $this->getObjectMap()->toObjects($result) : $result;
	}

	/**
	 * @param array|null $orderBy
	 * @param int $order
	 * @return array|false
	 */
	public function selectObjects(?array $orderBy = null, int $order = OrderBy::DESC)
	{
		$query = $this->getSelect();
		
		if ($orderBy)
			$query->orderBy($orderBy, $order);
		
		$result = $query->queryAll(true);
		
		return $result ? $this->getObjectMap()->toObjects($result) : $result;
	}
	
	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function updateObject($object, array $byFields)
	{
		$data = $this->getObjectMap()->toRow($object);
		
		$byFields = array_flip($byFields);
		$where = array_intersect_key($data, $byFields);
		$data = array_diff_key($data, $byFields);
		
		return $this->getConnector()
			->update()
			->table($this->getTableName())
			->set($data)
			->byFields($where)
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
	public function upsertObjectsForValues($objects, array $valueFields)
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