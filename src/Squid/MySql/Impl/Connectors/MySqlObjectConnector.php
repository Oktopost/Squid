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
	 * @return array
	 */
	protected function objectsToData(array $objects)
	{
		if ($this->hasMapper())
		{
			$data = $this->getMapper()->getArray($objects);
			
			foreach($data as $key => $object)
			{
				$data[$key] = array_diff_key($object, array_flip($this->getIgnoreFields()));
			}
		}
		else
		{
			$data = LiteObject::allToArray($objects, [], $this->getIgnoreFields());
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
		if ($excludeFields)
		{
			$this->addIgnoreFields($excludeFields);
		}
		
		$data = $this->objectsToData($objects);
		
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
			->queryRow(true, false);
		
		return (!$data ? $data : $this->createInstance($data));
	}

	/**
	 * @param array $byFields
	 * @param array $orderFields
	 * @param int $limit
	 * @return LiteObject[]|null
	 */
	public function loadAllByFields(array $byFields, array $orderFields = [], $limit = 32)
	{
		$query = $this->createQuery($byFields, $orderFields);
		return $this->createAllInstances($query->limitBy($limit)->queryAll(true) ?: []);
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
		
		if($excludeFields)
		{
			$this->addIgnoreFields($excludeFields);
		}
		
		$fields = array_diff($objects[0]->getPropertyNames(), $this->getIgnoreFields());
		$data = $this->objectsToData($objects);
		
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