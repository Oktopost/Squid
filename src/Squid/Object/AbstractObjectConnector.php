<?php
namespace Squid\Object;


use Objection\LiteObject;


abstract class AbstractObjectConnector implements IObjectConnector
{
	private $className;

	
	/**
	 * @return string
	 */
	protected function getDomain() { return $this->className; }

	/**
	 * @param array|bool $data
	 * @return LiteObject
	 */
	protected function createInstance($data = false) 
	{ 
		/** @var LiteObject $instance */
		$instance = new $this->className;
		
		if ($data)
		{
			$instance->fromArray($data);
		}
		
		return $instance;
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
	 * @inheritdoc
	 */
	public function insert(LiteObject $object, array $excludeFields = [])
	{
		return $this->insertAll([$object], $excludeFields);
	}
	
	/**
	 * @inheritdoc
	 */
	public function loadOneByField($field, $value, array $orderFields = []) 
	{
		return $this->loadOneByFields([$field => $value], $orderFields);
	}
	
	/**
	 * @inheritdoc
	 */
	public function loadAllByField($field, $value, array $orderFields = [], $limit = 32)
	{
		return $this->loadAllByFields([$field => $value], $orderFields, $limit);
	}
	
	/**
	 * @inheritdoc
	 */
	public function updateObjectByFields(LiteObject $object, array $keyFields)
	{
		return $this->updateByFields(
			$object->toArray([], $keyFields),
			$object->toArray($keyFields)
		);
	}
	
	/**
	 * @inheritdoc
	 */
	public function upsertByFields(LiteObject $object, array $keyFields)
	{
		return $this->upsertAll([$object], $keyFields);
	}
	
	/**
	 * @inheritdoc
	 */
	public function deleteByField($field, $value)
	{
		return $this->deleteByFields([$field => $value]);
	}
}