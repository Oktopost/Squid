<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Connectors\Object\IORMConnector;
use Squid\MySql\Connectors\Object\Selector\IQuerySelector;
use Squid\MySql\Impl\Connectors\Internal\Map\MapFactory;

use Squid\Exceptions\SquidException;


class ObjectQuerySelector implements IQuerySelector
{
	/** @var IRowMap */
	private $mapper;
	
	
	private function toObject(array $row)
	{
		return $this->mapper->toObject($row);
	}
	
	private function toObjects(array $rows)
	{
		return $this->mapper->toObjects($rows);
	}
	
	
	/**
	 * @param mixed|IORMConnector $mapper
	 */
	public function __construct($mapper)
	{
		if ($mapper instanceof IORMConnector)
		{
			$this->mapper = $mapper->getMapper();
		}
		else
		{
			$this->mapper = MapFactory::create($mapper);
		}
	}
	

	/**
	 * @param ICmdSelect $select
	 * @return array|false
	 */
	public function all(ICmdSelect $select)
	{
		$res = $select->query();
		
		return (is_array($res) ? $this->toObjects($res) : false);
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
			return $this->toObject($data[0]);
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
	 * @param callable $callback Called for each selected object. 
	 * If callback returns false, queryWithCallback will abort and return false.
	 * If callback returns 0, queryWithCallback will abort and return true.
	 * For any other value, callback will continue to the next row.
	 * @return bool
	 */
	public function withCallback(ICmdSelect $select, callable $callback): bool
	{
		return $select->queryWithCallback(
			function($row) 
				use ($callback)
			{
				return $callback($this->toObject($row));
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
			yield $this->toObjects($item);
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
				$parsedMap[$key] = $this->toObject($row);
			}
		}
		
		return $parsedMap ?: $map;
	}
}