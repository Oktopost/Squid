<?php
namespace Squid\MySql\Impl\Connectors\Object\Plain;


use Squid\MySql\Connectors\Object\IPlainObjectConnector;

use Squid\MySql\Impl\Connectors\Object\PlainObjectConnector;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


/**
 * @mixin AbstractORMConnector 
 * @mixin IPlainObjectConnector
 */
trait TPlainDecorator
{
	/** @var IPlainObjectConnector */
	private $_plainConnector;
	
	
	protected function getPlainConnector(): IPlainObjectConnector
	{
		if (!$this->_plainConnector)
			$this->_plainConnector = new PlainObjectConnector($this);
		
		return $this->_plainConnector;
	}
	
	
	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($object, bool $ignore = false)
	{
		return $this->getPlainConnector()->insertObjects($object, $ignore);
	}

	/**
	 * @param array $fields
	 * @return mixed|false
	 */
	public function selectObjectByFields(array $fields)
	{
		return $this->getPlainConnector()->selectObjectByFields($fields);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|false
	 */
	public function selectObjectByField(string $field, $value)
	{
		return $this->getPlainConnector()->selectObjectByField($field, $value);
	}

	/**
	 * @param array $fields
	 * @return mixed|false
	 */
	public function selectFirstObjectByFields(array $fields)
	{
		return $this->getPlainConnector()->selectFirstObjectByFields($fields);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|false
	 */
	public function selectFirstObjectByField(string $field, $value)
	{
		return $this->getPlainConnector()->selectFirstObjectByField($field, $value);
	}

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return array|false
	 */
	public function selectObjectsByFields(array $fields, ?int $limit = null)
	{
		return $this->getPlainConnector()->selectObjectsByFields($fields, $limit);
	}

	/**
	 * @param array|null $orderBy
	 * @return array|false
	 */
	public function selectObjects(?array $orderBy = null)
	{
		return $this->getPlainConnector()->selectObjects($orderBy);
	}
	
	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function updateObject($object, array $byFields)
	{
		return $this->getPlainConnector()->updateObject($object, $byFields);
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertObjectsByKeys($objects, array $keys)
	{
		return $this->getPlainConnector()->upsertObjectsByKeys($objects, $keys);
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertObjectsByValues($objects, array $valueFields)
	{
		return $this->getPlainConnector()->upsertObjectsByValues($objects, $valueFields);
	}
}