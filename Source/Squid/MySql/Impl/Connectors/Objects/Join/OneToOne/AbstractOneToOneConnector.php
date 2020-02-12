<?php
namespace Squid\MySql\Impl\Connectors\Objects\Join\OneToOne;


use Squid\OrderBy;
use Squid\Exceptions\SquidUsageException;

use Squid\MySql\Connectors\Objects\IQueryConnector;
use Squid\MySql\Connectors\Objects\Join\IJoinConnector;
use Squid\MySql\Connectors\Objects\Join\OneToOne\IOneToOneConnector;
use Squid\MySql\Connectors\Objects\Query\ICmdObjectSelect;
use Squid\MySql\Connectors\Objects\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Objects\Generic\IGenericIdentityConnector;

use Squid\MySql\Impl\Connectors\Objects\Join\Selector\JoinedObjectSelect;


abstract class AbstractOneToOneConnector implements 
	IOneToOneConnector,
	IQueryConnector
{
	/** @var IJoinConnector */
	private $config;
	
	/** @var IGenericIdentityConnector */
	private $childrenConnector;
	
	
	private function upsertChildren($parents, $affectedParents, $method): int
	{
		if ($affectedParents === false) 
			return false;
		
		$res = $this->config->$method($parents);
		
		return ($res === false ? false : $affectedParents + $res);
	}
	
	private function loaded($parents)
	{
		return $parents ? $this->config()->loaded($parents) : $parents;
	}
	
	
	protected function config(): IJoinConnector
	{
		return $this->config;
	}
	
	protected function childConnector(): IGenericIdentityConnector
	{
		return $this->childrenConnector;
	}
	
	
	protected abstract function getPrimary(): IGenericObjectConnector;


	/**
	 * @param IJoinConnector $connector
	 * @return static|AbstractOneToOneConnector
	 */
	public function setConfig(IJoinConnector $connector): AbstractOneToOneConnector
	{
		$this->config = $connector;
		return $this;
	}
	
	
	public function countByField(string $field, $value) { return $this->getPrimary()->countByField($field, $value); }
	public function countByFields(array $fields) { return $this->getPrimary()->countByFields($fields); }
	public function existsByField(string $field, $value): bool { return $this->getPrimary()->existsByField($field, $value); }
	public function existsByFields(array $fields): bool { return $this->getPrimary()->existsByFields($fields); }
	public function deleteByField(string $field, $value, ?int $limit = null) { return $this->getPrimary()->deleteByField($field, $value, $limit); }
	public function deleteByFields(array $fields, ?int $limit = null) { return $this->getPrimary()->deleteByFields($fields, $limit); }
	
	
	/**
	 * @param mixed|array $objects
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($objects, bool $ignore = false)
	{
		$count = $this->getPrimary()->insertObjects($objects, $ignore);
		
		if ($count === false) 
			return false;
		
		$res = $this->config->inserted($objects, $ignore);
		
		return ($res === false ? false : $count + $res);
	}

	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectObjectByFields(array $fields)
	{
		return $this->loaded($this->getPrimary()->selectObjectByFields($fields));
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectObjectByField(string $field, $value)
	{
		return $this->loaded($this->getPrimary()->selectObjectByField($field, $value));
	}

	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByFields(array $fields)
	{
		return $this->loaded($this->getPrimary()->selectFirstObjectByFields($fields));
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByField(string $field, $value)
	{
		return $this->loaded($this->getPrimary()->selectFirstObjectByField($field, $value));
	}

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return array|false
	 */
	public function selectObjectsByFields(array $fields, ?int $limit = null)
	{
		return $this->loaded($this->getPrimary()->selectObjectsByFields($fields, $limit));
	}

	/**
	 * @param array|null $orderBy
	 * @param int $order
	 * @return array|false
	 */
	public function selectObjects(?array $orderBy = null, int $order = OrderBy::DESC)
	{
		return $this->loaded($this->getPrimary()->selectObjects($orderBy, $order));
	}

	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function updateObject($object, array $byFields)
	{
		$count = $this->getPrimary()->updateObject($object, $byFields);
		return $this->upsertChildren($object, $count, 'updated');
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertObjectsByKeys($objects, array $keys)
	{
		$count = $this->getPrimary()->upsertObjectsByKeys($objects, $keys);
		return $this->upsertChildren($objects, $count, 'upserted');
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertObjectsForValues($objects, array $valueFields)
	{
		$count = $this->getPrimary()->upsertObjectsForValues($objects, $valueFields);
		return $this->upsertChildren($objects, $count, 'upserted');
	}
	
	
	public function query(): ICmdObjectSelect
	{
		$primary = $this->getPrimary();
		
		if (!($primary instanceof IQueryConnector))
		{
			throw new SquidUsageException('query in OneToOne connector can be used only if the ' . 
				'primary connector also implements IQueryConnector.');
		}
		
		return new JoinedObjectSelect($primary->query(), $this->config());
	}
}