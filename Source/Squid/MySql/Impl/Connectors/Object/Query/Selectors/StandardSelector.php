<?php
namespace Squid\MySql\Impl\Connectors\Object\Query\Selectors;


use Structura\Map;

use Squid\Exceptions\SquidException;

use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Connectors\Object\Query\IObjectSelector;

use Squid\MySql\Impl\Connectors\Utils\Object\TObjectMapConnector;


class StandardSelector implements IObjectSelector
{
	use TObjectMapConnector;
	
	
	public function __construct(?IRowMap $mapper = null)
	{
		if ($mapper)
		{
			$this->setObjectMap($mapper);
		}
	}
	
	/**
	 * @param ICmdSelect $select
	 * @return array|false
	 */
	public function all(ICmdSelect $select)
	{
		$data = $select->queryAll(true);
		return ($data ? $this->getMap()->toObjects($data) : $data);
	}

	/**
	 * @param ICmdSelect $select
	 * @return mixed|false
	 */
	public function one(ICmdSelect $select)
	{
		$res = null;
		$data = $this->all($select);
		
		if ($data === false)
		{
			return false;
		}
		else if (count($data) == 0)
		{
			return null;
		}
		else if (count($data) == 1)
		{
			return $data[0];
		}
		
		throw new SquidException('More then one row selected!');
	}

	/**
	 * @param ICmdSelect $select
	 * @return mixed|false
	 */
	public function first(ICmdSelect $select)
	{
		$object = null;
		
		$res = $this->withCallback(
			$select, 
			function ($result)
				use (&$object)
			{
				$object = $result;
				return 0;
			});
		
		return ($res ? $object : false);
	}

	/**
	 * @param ICmdSelect $select
	 * @param callable $callback
	 * @return bool
	 */
	public function withCallback(ICmdSelect $select, callable $callback): bool
	{
		return $select->queryWithCallback(
			function($row) 
				use ($callback)
			{
				return $callback($this->getMap()->toObject($row));
			},
			true);
	}

	/**
	 * @param ICmdSelect $select
	 * @return iterable
	 */
	public function iterator(ICmdSelect $select): iterable
	{
		$iterator = $select->queryIterator(true);
		
		foreach ($iterator as $item)
		{
			yield $this->getMap()->toObject($item);
		}
	}

	/**
	 * @param ICmdSelect $select
	 * @param string $field
	 * @param bool $removeColumnFromRow
	 * @return array|false
	 */
	public function map(ICmdSelect $select, string $field, bool $removeColumnFromRow = false)
	{
		$map = $select->queryMapRow($field, $removeColumnFromRow);
		$parsedMap = [];
		
		if ($map)
		{
			foreach ($map as $key => $row)
			{
				$parsedMap[$key] = $this->getMap()->toObject($row);
			}
		}
		
		return $parsedMap ?: $map;
	}
	
	/**
	 * @param ICmdSelect $select
	 * @param string|int $byColumn
	 * @param bool $removeColumn
	 * @return Map
	 */
	public function groupBy(ICmdSelect $select, $byColumn, bool $removeColumn = false): Map
	{
		$map = $select->queryGroupBy($byColumn, $removeColumn);
		
		if (!$map) 
			throw new SquidException('False values for queryGroupBy are not supported');
		
		foreach ($map as $key => $values)
		{
			$map->add($key, $this->getMap()->toObjects($values));
		}
		
		return $map;
	}
}