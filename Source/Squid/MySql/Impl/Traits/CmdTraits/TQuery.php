<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Objection\LiteObject;

use Structura\Map;

use Squid\MySql\Exceptions\MySqlException;
use Squid\Exceptions\SquidException;


/**
 * @method \PDOStatement|mixed execute()
 * @method string __toString()
 */
trait TQuery 
{
	private function queryValues($key, $value = null, bool $useMap = false, callable $operator)
	{
		$fetchMode = $this->resolveFetchMode(is_string($key) || is_string($value));
		$result = $this->execute();
		$map = [];
		
		try
		{
			while ($row = $result->fetch($fetchMode))
			{
				if (!key_exists($key, $row) || ($value && !key_exists($value, $row)))
				{
					throw new MySqlException(
						"Key '$key' or Value '$value' columns not found in the query result: " . 
						implode(array_keys($row)));
				}
				
				$operator($map, $row[$key] ?? 0, is_null($value) ? $row : $row[$value]);
				
			}
		}
		finally
		{
			$result->closeCursor();
		}
		
		return $useMap ? new Map($map) : $map;
	}
	
	private function resolveFetchMode($fetchMode)
	{
		if ($fetchMode === true) return \PDO::FETCH_ASSOC;
		else if ($fetchMode === false) return \PDO::FETCH_NUM;
		
		return $fetchMode;
	}
	
	private function parseObject(array $row, string $className): LiteObject
	{
		$object = new $className;
		
		foreach ($row as $key => $value)
		{
			$object->$key = $value;
		}
		
		return $object;
	}
	

	public function query()
	{
		return $this->queryAll(true);
	}
	
	public function queryNumeric()
	{
		return $this->queryAll(false);
	}
	
	public function queryAll($isAssoc = false) 
	{
		$result = $this->execute();
		
		if (!$result) return $result;
		
		return $result->fetchAll($this->resolveFetchMode($isAssoc));
	}
	
	public function queryRow($isAssoc = false, bool $failOnMultipleResults = true)
	{
		$result = $this->execute();
		
		if (!$result)
		{
			return $result;
		}
		else if ($failOnMultipleResults && $result->rowCount() > 1)
		{
			throw new SquidException('More than one row was selected! ' . $this->__toString());
		}
		
		$result = $result->fetch($this->resolveFetchMode($isAssoc));
		
		return ($result === false ? null : $result);
	}
	
	public function queryColumn(bool $failOnMultipleResults = true): array
	{
		$result = $this->execute();
		$data = [];
		
		if (!$result) 
		{
			return $result;
		}
		else if ($failOnMultipleResults && $result->columnCount() > 1)
		{
			throw new SquidException('More than one column was selected!');
		}
		
		while ($row = $result->fetch(\PDO::FETCH_NUM))
		{
			$data[] = $row[0];
		}
		
		return $data;
	}
	
	public function queryScalar($default = null, bool $failOnMultipleResults = true) 
	{
		$result = $this->execute();
		
		if (!$result) 
		{
			return $default;
		}
		else if ($failOnMultipleResults && ($result->rowCount() > 1 || $result->columnCount() > 1)) 
		{
			throw new SquidException('More than one column or row was selected!');
		} 
		else if ($result->rowCount() == 0) 
		{
			return $default;
		}
		
		return $result->fetch(\PDO::FETCH_NUM)[0];
	}
	
	public function queryInt(?int $default = null, bool $failOnMultipleResults = true): ?int
	{
		$result = $this->queryScalar($default, $failOnMultipleResults);
		return (is_null($result) ? null : (int)$result);
	}
	
	public function queryFloat(?float $default = null, bool $failOnMultipleResults = true): ?float
	{
		$result = $this->queryScalar($default, $failOnMultipleResults);
		return (is_null($result) ? null : (float)$result);
	}
	
	public function queryBool(?bool $default = null, bool $failOnMultipleResults = true): ?bool
	{
		$result = $this->queryScalar($default, $failOnMultipleResults);
		return (is_null($result) ? null : (bool)$result);
	}
	
	public function queryWithCallback(callable $callback, ?array &$result = null, bool $isAssoc = true)
	{
		$fetchMode = $this->resolveFetchMode($isAssoc);
		$cmdResult = $this->execute();
		$returnValues = [];
		
		if (!$cmdResult)
			return $cmdResult;
		
		while ($row = $cmdResult->fetch($fetchMode)) 
		{
			$value = call_user_func($callback, $row);
			
			if ($value === false) return false;
			else if ($value === 0) break;
			else if (is_array($result) && !is_scalar($value) && !is_null($value)) 
			{
				$returnValues[] = $value;
			}
		}
		
		if ($returnValues)
		{
			$result = $returnValues;
		}
		
		return true;
	}
	
	public function queryIterator($isAssoc = true) 
	{
		$fetchMode = $this->resolveFetchMode($isAssoc);
		$result = $this->execute();
		
		try 
		{
			while ($row = $result->fetch($fetchMode)) 
			{
				yield $row;
			}
		}
		// Free resources when generator released before reaching the end of the iteration.
		finally 
		{
			$result->closeCursor();
		}
	}
	
	public function queryIteratorBulk(int $size = 100, $isAssoc = true)
	{
		$page = 0;
		$found = $size;
		
		while ($found)
		{
			$query = clone $this;
			
			$query->page($page++, $size);
			$result = $query->queryAll($isAssoc);
			
			if ($result)
			{
				$found = ($size == count($result));
				yield $result;
			}
			else
			{
				$found = false;
			}
		}
	}
	
	public function queryValuesMap($key = 0, $value = 1, bool $useMap = false)
	{
		return $this->queryValues($key, $value, $useMap, function (array &$map, $key, $value)
		{
			$map[$key] = $value;
		});
	}
	
	public function queryValuesGroup($key = 0, $value = 1, bool $useMap = false)
	{
		return $this->queryValues($key, $value, $useMap, function (array &$map, $key, $value)
		{
			$map[$key][] = $value;
		});
	}
	
	public function queryRecordsMap($key = 0, bool $excludeKey = false, bool $useMap = false)
	{
		return $this->queryValues($key, null, $useMap, 
			function (array &$map, $keyValue, $row) 
				use ($key, $excludeKey)
			{
				if ($excludeKey)
					unset($row[$key]);
				
				$map[$keyValue] = $row;
			});
	}
	
	public function queryRecordsGroup($key = 0, bool $excludeKey = false, bool $useMap = false)
	{
		return $this->queryValues($key, null, $useMap, 
			function (array &$map, $keyValue, $row) 
				use ($key, $excludeKey)
			{
				if ($excludeKey)
					unset($row[$key]);
				
				$map[$keyValue][] = $row;
			});
	}
	
	
	public function queryObject(string $className)
	{
		$result = $this->queryRow(true);
		
		if (!$result)
			return null;
		
		return $this->parseObject($result, $className);
	}
	
	public function queryObjects(string $className): array
	{
		$data = $this->query();
		$result = [];
		
		foreach ($data as $row)
		{
			$result[] = $this->parseObject($row, $className);
		}
		
		return $result;
	}
	
	public function queryGroupBy($byColumn, bool $removeColumn = false): Map
	{
		$fetchMode = $this->resolveFetchMode(is_string($byColumn));
		$result = $this->execute();
		$map = new Map();
		
		try
		{
			while ($row = $result->fetch($fetchMode))
			{
				if (!isset($row[$byColumn])) 
					throw new MySqlException(
						"Column '$byColumn' not found in the query result: " . 
						implode(array_keys($row)));
				
				$key = $row[$byColumn];
				
				if ($removeColumn)
				{
					unset($row[$byColumn]);
				}
				
				if (!$map->has($key))
				{
					$map->add($key, [$row]);
				}
				else
				{
					$map->add($key, array_merge($map->get($key), [$row]));
				}
			}
		}
		// Free resources when generator released before reaching the end of the iteration.
		finally
		{
			$result->closeCursor();
		}
		
		return $map;
	}
	
	
	
	public function queryMap($key = 0, $value = 1)
	{
		return $this->queryValuesMap($key, $value, false);	
	}
	
	public function queryMapRow($key = 0, $removeColumnFromRow = false)
	{
		return $this->queryRecordsGroup($key, $removeColumnFromRow, false);
	}
}