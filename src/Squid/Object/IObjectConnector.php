<?php
namespace Squid\Object;


use Objection\Mapper;
use Objection\LiteObject;


interface IObjectConnector
{
	/**
	 * @param string $className
	 * @return static
	 */
	public function setDomain($className);
	
	/**
	 * @param LiteObject $object
	 * @param array $excludeFields
	 * @return bool|int
	 */
	public function insert(LiteObject $object, array $excludeFields = []);
	
	/**
	 * @param LiteObject[] $objects
	 * @param array $excludeFields
	 * @return int|bool
	 */
	public function insertAll(array $objects, array $excludeFields = []);
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @param array $orderFields
	 * @return LiteObject|null
	 */
	public function loadOneByField($field, $value, array $orderFields = []);
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @param array $orderFields
	 * @return LiteObject|null
	 */
	public function loadFirstByField($field, $value, array $orderFields = []);
	
	/**
	 * @param array $byFields
	 * @param array $orderFields
	 * @return LiteObject|null
	 */
	public function loadOneByFields(array $byFields, array $orderFields = []);
	
	/**
	 * @param array $byFields
	 * @param array $orderFields
	 * @return LiteObject|null
	 */
	public function loadFirstByFields(array $byFields, array $orderFields = []);
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @param array $orderFields
	 * @param int $limit
	 * @return LiteObject|null
	 */
	public function loadAllByField($field, $value, array $orderFields = [], $limit = 32);
	
	/**
	 * @param array $byFields
	 * @param array $orderFields
	 * @param int $limit
	 * @return LiteObject|null
	 */
	public function loadAllByFields(array $byFields, array $orderFields = [], $limit = 32);
	
	/**
	 * @param array $set
	 * @param array $byFields
	 * @return int|null
	 */
	public function updateByFields(array $set, array $byFields);
	
	/**
	 * @param LiteObject $object
	 * @param array $keyFields
	 * @return bool
	 */
	public function updateObjectByFields(LiteObject $object, array $keyFields);
	
	/**
	 * @param LiteObject $object
	 * @param array $keyFields
	 * @param array $excludeFields
	 * @return bool
	 */
	public function upsertByFields(LiteObject $object, array $keyFields, array $excludeFields = []);
	
	/**
	 * @param LiteObject[] $objects
	 * @param array $keyFields
	 * @param array $excludeFields
	 * @return bool
	 */
	public function upsertAll(array $objects, array $keyFields, array $excludeFields = []);
	
	/**
	 * @param string $field
	 * @param string $value
	 * @return bool
	 */
	public function deleteByField($field, $value);
	
	/**
	 * @param array $fields
	 * @return bool
	 */
	public function deleteByFields(array $fields);
	
	/**
	 * @return bool
	 */
	public function hasMapper();
	
	/**
	 * @param Mapper $mapper
	 * @return static
	 */
	public function setMapper(Mapper $mapper);
	
	/**
	 * @return Mapper Creates a default one if none defined.
	 */
	public function getMapper();
}