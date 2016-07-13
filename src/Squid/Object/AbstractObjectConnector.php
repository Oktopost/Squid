<?php
namespace Squid\Object;


use Objection\LiteObject;


abstract class AbstractObjectConnector implements IObjectConnector
{
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
	public function upsert(LiteObject $object, array $keyFields)
	{
		return $this->upsertAll([$object], $keyFields);
	}
	
	/**
	 * @inheritdoc
	 */
	public function deleteByField($field, $value)
	{
		return $this->deleteByFields([$field, $value]);
	}
}