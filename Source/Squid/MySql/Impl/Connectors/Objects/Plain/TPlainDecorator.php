<?php
namespace Squid\MySql\Impl\Connectors\Objects\Plain;


use Squid\OrderBy;

use Squid\MySql\Connectors\Objects\IPlainObjectConnector;

use Squid\MySql\Impl\Connectors\Objects\PlainObjectConnector;
use Squid\MySql\Impl\Connectors\Internal\Objects\AbstractORMConnector;


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
	 * @param int $order
	 * @return array|false
	 */
	public function selectObjects(?array $orderBy = null, int $order = OrderBy::DESC)
	{
		return $this->getPlainConnector()->selectObjects($orderBy, $order);
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
	public function upsertObjectsForValues($objects, array $valueFields)
	{
		return $this->getPlainConnector()->upsertObjectsForValues($objects, $valueFields);
	}
}