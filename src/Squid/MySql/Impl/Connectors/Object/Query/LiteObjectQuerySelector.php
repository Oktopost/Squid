<?php
namespace Squid\MySql\Impl\Connectors\Object\Query;


use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Connectors\Object\IQuerySelector;
use Squid\MySql\Impl\Connectors\Map\MapFactory;

use Squid\Exceptions\SquidException;

use Objection\Mapper;
use Objection\Mappers;
use Objection\LiteObject;


class LiteObjectQuerySelector implements IQuerySelector
{
	private $domain;
	
	/** @var IRowMap */
	private $mapper;
	
	
	private function toObject(array $row)
	{
		return $this->getMapper()->toObject($row);
	}
	
	private function toObjects(array $rows)
	{
		return $this->getMapper()->toObjects($rows);
	}
	
	private function getMapper(): IRowMap
	{
		if (!$this->mapper)
			$this->mapper = MapFactory::create(Mappers::simple());
		
		return $this->mapper;
	}
	
	
	/**
	 * @param LiteObject|string $className
	 */
	public function setDomain($className)
	{
		$this->domain = $className;
	}


	/**
	 * @param Mapper|IRowMap $mapper
	 */
	public function setMapper($mapper)
	{
		$this->mapper = MapFactory::create($mapper);
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
		return $this->one($select->limitBy(1));
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