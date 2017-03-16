<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\MySql\Exceptions\MySqlException;
use Squid\Exceptions\SquidException;


/**
 * @method \PDOStatement|mixed execute()
 * @method string __toString()
 * @see \Squid\MySql\Command\IQuery
 */
trait TQuery 
{
	/**
	 * @inheritdoc
	 */
	private function resolveFetchMode($fetchMode)
	{
		if ($fetchMode === true) return \PDO::FETCH_ASSOC;
		else if ($fetchMode === false) return \PDO::FETCH_NUM;
		
		return $fetchMode;
	}


	/**
	 * Identical to queryAll(true);
	 * @return array
	 */
	public function query()
	{
		return $this->queryAll(true);
	}

	/**
	 * @param bool|int $isAssoc Will accept \PDO::FETCH_*
	 * @return array|bool
	 */
	public function queryAll($isAssoc = false) 
	{
		$result = $this->execute();
		
		if (!$result) return $result;
		
		return $result->fetchAll($this->resolveFetchMode($isAssoc));
	}
	
	/**
	 * @inheritdoc
	 * @throws SquidException
	 */
	public function queryRow($isAssoc = false, $oneOrNone = true)
	{
		$result = $this->execute();
		
		if (!$result)
		{
			return $result;
		}
		else if ($oneOrNone && $result->rowCount() > 1)
		{
			throw new SquidException('More than one row was selected! ' . $this->__toString());
		}
		
		return $result->fetch($this->resolveFetchMode($isAssoc));
	}
	
	/**
	 * @inheritdoc
	 * @throws SquidException
	 */
	public function queryColumn($oneOrNone = true)
	{
		$result = $this->execute();
		$data = [];
		
		if (!$result) 
		{
			return $result;
		}
		else if ($oneOrNone && $result->columnCount() > 1)
		{
			throw new SquidException('More than one column was selected!');
		}
		
		while ($row = $result->fetch(\PDO::FETCH_NUM))
		{
			$data[] = $row[0];
		}
		
		return $data;
	}
	
	/**
	 * @inheritdoc
	 * @throws SquidException
	 */
	public function queryScalar($default = false, $expectOne = true) 
	{
		$result = $this->execute();
		
		if (!$result) 
		{
			return $default;
		}
		else if ($expectOne && $result->rowCount() != 1 && $result->columnCount() != 1) 
		{
			throw new SquidException('More than one column or row was selected!');
		} 
		else if ($result->rowCount() == 0) 
		{
			return $default;
		}
		
		return $result->fetch(\PDO::FETCH_NUM)[0];
	}
	
	/**
	 * @inheritdoc
	 */
	public function queryInt($expectOne = true) 
	{
		$result = $this->queryScalar(false, $expectOne);
		return ($result === false ? false : (int)$result);
	}
	
	/**
	 * @inheritdoc
	 */
	public function queryBool($expectOne = true)
	{
		$result = $this->queryScalar(false, $expectOne);
		return ($result === false ? false : (bool)$result);
	}
	
	/**
	 * @inheritdoc
	 */
	public function queryWithCallback($callback, $isAssoc = true) 
	{
		$fetchMode = $this->resolveFetchMode($isAssoc);
		$result = $this->execute();
		
		if (!$result)
			return $result;
		
		while ($row = $result->fetch($fetchMode)) 
		{
			$value = call_user_func($callback, $row);
			
			if ($value === false)	return false;
			else if ($value === 0)	break;
		}
		
		return true;
	}
	
	/**
	 * @inheritdoc
	 */
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
	
	
	/**
	 * Return an array where the result of one column is the index and the second is value.
	 * @param int|string $key Name of the key column.
	 * @param int|string $value Name of the value column
	 * @return array|false
	 */
	public function queryMap($key = 0, $value = 1)
	{
		$fetchMode = $this->resolveFetchMode(is_string($key) || is_string($value));
		$result = $this->execute();
		$map = [];
		
		try
		{
			while ($row = $result->fetch($fetchMode))
			{
				if (!isset($row[$key]) || !key_exists($value, $row)) 
					throw new MySqlException(
						"Key '$key' or Value '$value' columns not found in the query result: " . 
						implode(array_keys($row)));
				
				$map[$row[$key]] = $row[$value];
			}
		}
		// Free resources when generator released before reaching the end of the iteration.
		finally
		{
			$result->closeCursor();
		}
		
		return $map;
	}
	
	/**
	 * Return an array where the result of one column is the index and the remaining data is value.
	 * @param int|string $key Name of the key column.
	 * @param bool $removeColumnFromRow Should remove the key column from values.
	 * @return array|false
	 */
	public function queryMapRow($key = 0, $removeColumnFromRow = false)
	{
		$fetchMode = $this->resolveFetchMode(is_string($key));
		$result = $this->execute();
		$map = [];
		
		try
		{
			while ($row = $result->fetch($fetchMode))
			{
				if (!isset($row[$key]))
					throw new MySqlException(
						"Key '$key' column not found in the query result: " .
						implode(array_keys($row)));
				
				if ($removeColumnFromRow)
				{
					$map[$row[$key]] = $row;
					unset($map[$row[$key]][$key]);
				}
				else
				{
					$map[$row[$key]] = $row;
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
}