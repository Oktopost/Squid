<?php
namespace Squid\MySql\Impl\Connectors;


use Squid\MySql\IMySqlConnector;
use Squid\MySql\Utils\ClassName;
use Squid\MySql\Command\IWithWhere;
use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Exceptions\QueryFailedException;
use Squid\MySql\Connectors\IMySqlObjectConnector;

use Squid\Object\AbstractObjectConnector;

use Objection\LiteObject;


class MySqlObjectConnector extends AbstractObjectConnector implements IMySqlObjectConnector
{
	private $tableName;
	
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
	}
	
	/**
	 * @inheritdoc
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
		
		return (!$data ? $data : $this->createInstance($data));
	}
	
	/**
	 * @inheritdoc
	 */
	public function loadAllByFields(array $byFields, array $orderFields = [], $limit = 32)
	{
		$data = [];
		$query = $this->createQuery($byFields);
		
		try 
		{
			foreach ($query->queryIterator() as $item) 
			{
				$data[] = $this->createInstance($item);
			}
		}
		catch (QueryFailedException $e) 
		{
			return false;
		}
		
		return $data;
	}
	
	/**
	 * @inheritdoc
	 */
	public function updateByFields(array $set, array $byFields)
	{
<<<<<<< HEAD:src/Squid/MySql/Connectors/ObjectConnector.php
		$update = $this->connector
			->update()
=======
		$update = $this->connector->update()
>>>>>>> dev:src/Squid/MySql/Impl/Connectors/MySqlObjectConnector.php
			->table($this->tableName)
			->set($set);
		
		$this->createFilter($update, $byFields);
		
<<<<<<< HEAD:src/Squid/MySql/Connectors/ObjectConnector.php
		return $update->executeDml(true);
=======
		return $update->executeDml();
>>>>>>> dev:src/Squid/MySql/Impl/Connectors/MySqlObjectConnector.php
	}
	
	/**
	 * @param LiteObject[] $objects
	 * @param array $keyFields
	 * @return bool
	 */
	public function upsertAll(array $objects, array $keyFields)
	{
<<<<<<< HEAD:src/Squid/MySql/Connectors/ObjectConnector.php
		$upsert = $this->connector
			->upsert()
			->into($this->tableName);
=======
		$upsert = $this->connector->upsert()
			->into($this->tableName)
			->setDuplicateKeys($keyFields);
>>>>>>> dev:src/Squid/MySql/Impl/Connectors/MySqlObjectConnector.php
		
		foreach ($objects as $object)
		{
			$upsert->values($object->toArray());
		}
		
<<<<<<< HEAD:src/Squid/MySql/Connectors/ObjectConnector.php
		return $upsert
			->setDuplicateKeys($keyFields)
			->executeDml(true);
=======
		return $upsert->executeDml();
>>>>>>> dev:src/Squid/MySql/Impl/Connectors/MySqlObjectConnector.php
	}
	
	/**
	 * @inheritdoc
	 */
	public function deleteByFields(array $fields)
	{
<<<<<<< HEAD:src/Squid/MySql/Connectors/ObjectConnector.php
		$delete = $this->connector
			->delete()
			->from($this->tableName);
		
		$this->createFilter($delete, $fields);
		
=======
		$delete = $this->connector->delete();
		$this->createFilter($delete, $fields);
>>>>>>> dev:src/Squid/MySql/Impl/Connectors/MySqlObjectConnector.php
		return $delete->executeDml();
	}
}