<?php
namespace Squid\MySql\Connectors\Object;


/**
 * @mixin IObjectConnector
 */
trait TGenericObjectConnector
{
	/**
	 * @param array $fields
	 * @return mixed|false
	 */
	public function selectOneByFields(array $fields)
	{
		return $this->query()->byFields($fields)->queryOne();
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|false
	 */
	public function selectOneByField(string $field, $value)
	{
		return $this->query()->byField($field, $value)->queryOne();
	}
	
	/**
	 * @param array $fields
	 * @return mixed|false
	 */
	public function selectFirstByFields(array $fields)
	{
		return $this->query()->byFields($fields)->queryFirst();
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return mixed|false
	 */
	public function selectFirstByField(string $field, $value)
	{
		return $this->query()->byField($field, $value)->queryFirst();
	}

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return array|false
	 */
	public function selectAllByFields(array $fields, ?int $limit = null)
	{
		$query = $this->query()->byFields($fields);
		
		if ($limit)
			$query->limitBy($limit);
		
		return $query->queryAll();
	}
	
	/**
	 * @param array|null $orderBy
	 * @return array|false
	 */
	public function selectAll(?array $orderBy = null)
	{
		$query = $this->query();
		
		if ($orderBy)
			$query->orderBy($orderBy);
		
		return $query->queryAll();
	}
}