<?php
namespace Squid\MySql\Connectors;


use Squid\MySql\IMySqlConnector;
use Squid\MySql\Utils\QueryFailedException;
use Squid\MySql\Command\IWithWhere;
use Squid\MySql\Command\ICmdSelect;

use Squid\Object\AbstractObjectConnector;

use Objection\LiteObject;


class ObjectConnector extends AbstractObjectConnector
{
	private $tableName;
	private $className;
	
	/** @var IMySqlConnector */
	private $connector;
	
	
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
	 * @param string $tableName
	 * @param IMySqlConnector $connector
	 */
	public function __construct($tableName, IMySqlConnector $connector)
	{
		$this->tableName = $tableName;
		$this->connector = $connector;
	}
	
	
	/**
	 * @param string $className
	 * @return static
	 */
	public function setDomain($className)
	{
		$this->className = $className;
	}
	
	/**
	 * @param LiteObject[] $objects
	 * @param array $excludeFields
	 * @return int|bool
	 */
	public function insertAll(array $objects, array $excludeFields = [])
	{
		$insert = $this->connector
			->insert()
			->into($this->tableName);
		
		foreach ($objects as $object) 
		{
			$insert->values($object->toArray($excludeFields));
		}
		
		return $insert->executeDml(true);
	}
	
	/**
	 * @inheritdoc
	 */
	public function loadOneByFields(array $byFields, array $orderFields = [])
	{
		$data = $this
			->createQuery($byFields)
			->queryRow(true, true);
		
		if (!$data) return $data;
		
		/** @var LiteObject $object */
		$object = new $this->className;
		$object->fromArray($data);
		
		return $object;
	}
	
	/**
	 * @param array $byFields
	 * @param array $orderFields
	 * @param int $limit
	 * @return null|LiteObject
	 */
	public function loadAllByFields(array $byFields, array $orderFields = [], $limit = 32)
	{
		$data = [];
		$query = $this->createQuery($byFields);
		
		try 
		{
			foreach ($query->queryIterator() as $item) 
			{
				$data[] = new $this->className($item);
			}
		}
		catch (QueryFailedException $e) 
		{
			return false;
		}
		
		return $data;
	}
	
	/**
	 * @param array $set
	 * @param array $byFields
	 * @return int|null
	 */
	public function updateByFields(array $set, array $byFields)
	{
		$update = $this->connector
			->update()
			->table($this->tableName)
			->set($set);
		
		$this->createFilter($update, $byFields);
		
		return $update->executeDml(true);
	}
	
	/**
	 * @param LiteObject[] $objects
	 * @param array $keyFields
	 * @return bool
	 */
	public function upsertAll(array $objects, array $keyFields)
	{
		$upsert = $this->connector
			->upsert()
			->into($this->tableName);
		
		foreach ($objects as $object)
		{
			$upsert->values($object->toArray());
		}
		
		return $upsert
			->setDuplicateKeys($keyFields)
			->executeDml(true);
	}
	
	/**
	 * @param array $fields
	 * @return bool
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