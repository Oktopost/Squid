<?php
namespace Squid\Object;


use Objection\Mapper;
use Objection\LiteObject;


abstract class AbstractObjectConnector implements IObjectConnector
{
	private $className;
	
	/** @var Mapper */
	private $mapper = null;
	
	private $ignoreFields = [];
	
	
	/**
	 * @return string
	 */
	protected function getDomain()
	{
		return $this->className;
	}
	
	/**
	 * @param array|bool $data
	 * @return LiteObject
	 */
	protected function createInstance($data = false)
	{
		if ($this->mapper)
		{
			return $this->mapper->getObject($data);
		}
		else
		{
			/** @var LiteObject $instance */
			$instance = new $this->className;
			
			if ($data)
			{
				$instance->fromArray($data);
			}
			
			return $instance;
		}
	}
	
	/**
	 * @param array $data
	 * @return LiteObject[]
	 */
	protected function createAllInstances(array $data)
	{
		if ($this->mapper)
		{
			return $this->mapper->getObjects($data);
		}
		else
		{
			/** @var LiteObject $className */
			$className = $this->className;
			return $className::allFromArray($data);
		}
	}


	/**
	 * @param string $className
	 * @return static
	 */
	public function setDomain($className)
	{
		$this->className = $className;
		return $this;
	}
	
	
	/**
	 * @param LiteObject $object
	 * @param array $excludeFields
	 * @return bool|int
	 */
	public function insert(LiteObject $object, array $excludeFields = [])
	{
		if ($excludeFields)
		{
			$this->addIgnoreFields($excludeFields);
		}
		
		return $this->insertAll([$object]);
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @param array $orderFields
	 * @return LiteObject|null
	 */
	public function loadOneByField($field, $value, array $orderFields = []) 
	{
		return $this->loadOneByFields([$field => $value], $orderFields);
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @param array $orderFields
	 * @return LiteObject|null
	 */
	public function loadFirstByField($field, $value, array $orderFields = [])
	{
		return $this->loadFirstByFields([$field => $value], $orderFields);
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @param array $orderFields
	 * @param int $limit
	 * @return LiteObject|null
	 */
	public function loadAllByField($field, $value, array $orderFields = [], $limit = 32)
	{
		return $this->loadAllByFields([$field => $value], $orderFields, $limit);
	}
	
	/**
	 * @param LiteObject $object
	 * @param array $keyFields
	 * @return bool
	 */
	public function updateObjectByFields(LiteObject $object, array $keyFields)
	{
		return (bool)$this->updateByFields(
			$object->toArray([], array_merge($keyFields, $this->getIgnoreFields())),
			$object->toArray($keyFields)
		);
	}
	
	/**
	 * @param LiteObject $object
	 * @param array $keyFields
	 * @param array $excludeFields
	 * @return bool
	 */
	public function upsertByFields(LiteObject $object, array $keyFields, array $excludeFields = [])
	{
		if ($excludeFields)
		{
			$this->addIgnoreFields($excludeFields);
		}
		
		return $this->upsertAll([$object], $keyFields);
	}
	
	/**
	 * @param string $field
	 * @param string $value
	 * @return bool
	 */
	public function deleteByField($field, $value)
	{
		return $this->deleteByFields([$field => $value]);
	}
	
	/**
	 * @return bool
	 */
	public function hasMapper()
	{
		return ($this->mapper != null);
	}
	
	/**
	 * @param Mapper $mapper
	 * @return static
	 */
	public function setMapper(Mapper $mapper)
	{
		if (!$mapper->getDefaultClassName())
			$mapper->setDefaultClassName($this->className);
		
		$this->mapper = $mapper;
		
		return $this;
	}
	
	/**
	 * @return Mapper Creates a default one if none defined.
	 */
	public function getMapper()
	{
		if (!$this->mapper)
		{
			$this->mapper = Mapper::createFor($this->className, new Mapper\Mappers\DummyMapper());
		}
		
		return $this->mapper;
	}
	
	/**
	 * @param array $ignoreFields
	 * @return static
	 */
	public function setIgnoreFields(array $ignoreFields)
	{
		$this->ignoreFields = $ignoreFields;
		return $this;
	}
	
	/**
	 * @param array $ignoreFields
	 * @return static
	 */
	public function addIgnoreFields(array $ignoreFields)
	{
		$newFields = array_diff($ignoreFields, $this->ignoreFields);
		
		foreach($newFields as $field)
		{
			$this->ignoreFields[] = $field;
		}
		
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getIgnoreFields()
	{
		return $this->ignoreFields;
	}
}