<?php
namespace Squid\MySql\Impl\Connectors;


use Squid\MySql\IMySqlConnector;
use Squid\MySql\Utils\ClassName;
use Squid\MySql\Command\IWithWhere;
use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Connectors\IMySqlObjectConnector;

use Squid\Object\AbstractObjectConnector;

use Objection\LiteObject;


class MySqlObjectConnector extends AbstractObjectConnector implements IMySqlObjectConnector
{
	private $tableName;
	
	/** @var IMySqlConnector */
	private $connector;
	
	
	/**
	 * @param LiteObject[] $objects
	 * @param array $excludeFields
	 * @return array
	 */
	private function objectsToData(array $objects, $excludeFields = [])
	{
		if ($this->hasMapper())
		{
			$data = $this->getMapper()->getArray($objects);
			
			if ($excludeFields)
			{
				$data = array_diff_key($data, array_flip($excludeFields));
			}
		}
		else
		{
			$data = LiteObject::allToArray($objects, [], $excludeFields);
		}
		
		return $data;
	}
	
	
	/**
	 * @param IWithWhere $query
	 * @param array $where
	 */
	protected function createFilter(IWithWhere $query, $where) 
	{
		foreach ($where as $field => $value) 
		{
			if (is_array($value))
			{
				$query->whereIn($field, $value);
			}
			else 
			{
				$query->byField($field, $value);
			}
		}
	}
	
	/**
	 * @param array $fields
	 * @param array|string|bool $order
	 * @return ICmdSelect
	 */
	protected function createQuery(array $fields, $order = false) 
	{
		$query = $this->connector
			->select()
			->from($this->tableName);
		
		$this->createFilter($query, $fields);
		
		if (is_string($order)) 
		{
			$query->orderBy($order);
		}
		else if (is_array($order)) 
		{
			foreach ($order as $field => $type) 
			{
				$query->orderBy($field, $type);
			}
		}
		
		return $query;
	}
	
	
	/**
	 * @return IMySqlConnector
	 */
	public function getConnector()
	{
		return $this->connector;
	}
	
	/**
	 * @inheritdoc
	 */
	public function setConnector(IMySqlConnector $connector) 
	{ 
		$this->connector = $connector;
		return $this; 
	}

	/**
	 * @inheritdoc
	 */
	public function setTable($tableName) 
	{
		$this->tableName = $tableName;
		return $this;
	}
	
	/**
	 * @inheritdoc
	 */
	public function setDomain($className)
	{
		parent::setDomain($className);
		
		if (!$this->tableName)
		{
			$this->tableName = ClassName::getClassNameOnly($className);
		}
		
		return $this;
	}
	
	/**
	 * @param LiteObject[] $objects
	 * @param array $excludeFields
	 * @return int|bool
	 */
	public function insertAll(array $objects, array $excludeFields = [])
	{
		$data = $this->objectsToData($objects, $excludeFields);
		
		return $this->connector
			->insert()
			->into($this->tableName)
			->valuesBulk($data)
			->executeDml(true);
	}
	
	/**
	 * @inheritdoc
	 */
	public function loadOneByFields(array $byFields, array $orderFields = [])
	{
		$data = $this
			->createQuery($byFields, $orderFields)
			->queryRow(true, true);
		
		return (!$data ? $data : $this->createInstance($data));
	}
	
	/**
	 * @param array $byFields
	 * @param array $orderFields
	 * @return LiteObject|null
	 */
	public function loadFirstByFields(array $byFields, array $orderFields = [])
	{
		$data = $this
			->createQuery($byFields, $orderFields)
			->limitBy(1)
			->queryRow(true, true);
		
		return (!$data ? $data : $this->createInstance($data));
	}

	/**
	 * @param array $byFields
	 * @param array $orderFields
	 * @param int $limit
	 * @return LiteObject|null
	 */
	public function loadAllByFields(array $byFields, array $orderFields = [], $limit = 32)
	{
		$query = $this->createQuery($byFields, $orderFields);
		return $this->createAllInstances($query->limitBy($limit)->queryAll(true));
	}
	
	/**
	 * @inheritdoc
	 */
	public function updateByFields(array $set, array $byFields)
	{
		$update = $this->connector->update()
			->table($this->tableName)
			->set($set);
		
		$this->createFilter($update, $byFields);
		
		return $update->executeDml(true);
	}
	
	/**
	 * @param LiteObject[] $objects
	 * @param array $keyFields
	 * @param array $excludeFields
	 * @return bool
	 */
	public function upsertAll(array $objects, array $keyFields, array $excludeFields = [])
	{
		if (!$objects) return true;
		
		$fields = array_diff($objects[0]->getPropertyNames(), $excludeFields);
		$data = $this->objectsToData($objects, $excludeFields);
		
		return $this->connector
			->upsert()
			->into($this->tableName, $fields)
			->valuesBulk($data)
			->setDuplicateKeys($keyFields)
			->executeDml(true);
	}
	
	/**
	 * @inheritdoc
	 */
	public function deleteByFields(array $fields)
	{
		$delete = $this->connector
			->delete()
			->from($this->tableName);
		
		$this->createFilter($delete, $fields);
		
		return $delete->executeDml();
	}
}