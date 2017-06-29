<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\OneToOne;


use Squid\MySql\Connectors\Object\Join\OneToOne\IOneToOneConfig;
use Squid\MySql\Connectors\Object\Join\OneToOne\IOneToOneConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericIdentityConnector;


abstract class AbstractOneToOneConnector implements IOneToOneConnector
{
	/** @var IOneToOneConfig */
	private $config;
	
	/** @var IGenericIdentityConnector */
	private $childrenConnector;
	

	/**
	 * @param mixed|array|false $parents
	 * @return bool
	 */
	protected function populate($parents)
	{
		if ($parents === false) return false;
		
		$byFields = $this->config->getWhereForChildren($parents);
		
		if ($byFields)
		{
			$result = $this->childrenConnector->selectObjectsByFields($byFields);
			
			if ($result === false)
				return false;
			
			$this->config->combine($parents, $result);
		}
		
		return $parents;
	}
	
	private function upsertChildren($parents, $parentOperationResultCount): int
	{
		if ($parentOperationResultCount === false) 
			return false;
		
		$childrenCount = 0;
		$modifiedChildren = $this->config->afterParentSaved($parents);
		
		if ($modifiedChildren)
		{
			$childrenCount = $this->childrenConnector->upsert($childrenCount);
		}
		
		return ($childrenCount === false ? false : $parentOperationResultCount + $childrenCount);
	}

	
	protected function config(): IOneToOneConfig
	{
		return $this->config;
	}
	
	protected function childConnector(): IGenericIdentityConnector
	{
		return $this->childrenConnector;
	}
	
	
	protected abstract function getPrimary(): IGenericObjectConnector;
	
	
	public function countByField(string $field, $value) { return $this->getPrimary()->countByField($field, $value); }
	public function countByFields(array $fields) { return $this->getPrimary()->countByFields($fields); }
	public function existsByField(string $field, $value): bool { return $this->getPrimary()->existsByField($field, $value); }
	public function existsByFields(array $fields): bool { return $this->getPrimary()->existsByFields($fields); }
	public function deleteByField(string $field, $value, ?int $limit = null) { $result = $this->getPrimary()->deleteByField($field, $value, $limit); }
	public function deleteByFields(array $fields, ?int $limit = null) { return $this->getPrimary()->deleteByFields($fields, $limit); }
	
	
	/**
	 * @param mixed|array $objects
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($objects, bool $ignore = false)
	{
		$childrenCount = 0;
		$count = $this->getPrimary()->insertObjects($objects, $ignore);
		
		if ($count === false) 
			return false;
		
		$modifiedChildren = $this->config->afterParentSaved($objects);
		
		if ($modifiedChildren)
		{
			$childrenCount = $this->childrenConnector->insertObjects($childrenCount, $ignore);
		}
		
		return ($childrenCount === false ? false : $count + $childrenCount);
	}

	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectObjectByFields(array $fields)
	{
		return $this->populate($this->getPrimary()->selectObjectByFields($fields));
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectObjectByField(string $field, $value)
	{
		return $this->populate($this->getPrimary()->selectObjectByField($field, $value));
	}

	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByFields(array $fields)
	{
		return $this->populate($this->getPrimary()->selectFirstObjectByFields($fields));
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByField(string $field, $value)
	{
		return $this->populate($this->getPrimary()->selectFirstObjectByField($field, $value));
	}

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return array|false
	 */
	public function selectObjectsByFields(array $fields, ?int $limit = null)
	{
		return $this->populate($this->getPrimary()->selectObjectsByFields($fields, $limit));
	}

	/**
	 * @param array|null $orderBy
	 * @return array|false
	 */
	public function selectObjects(?array $orderBy = null)
	{
		return $this->populate($this->getPrimary()->selectObjects($orderBy));
	}

	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function updateObject($object, array $byFields)
	{
		$count = $this->getPrimary()->updateObject($object, $byFields);
		return $this->upsertChildren($object, $count);
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertObjectsByKeys($objects, array $keys)
	{
		$count = $this->getPrimary()->upsertObjectsByKeys($objects, $keys);
		return $this->upsertChildren($objects, $count);
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertObjectsByValues($objects, array $valueFields)
	{
		$count = $this->getPrimary()->upsertObjectsByValues($objects, $valueFields);
		return $this->upsertChildren($objects, $count);
	}
}