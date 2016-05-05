<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\MySql\Exceptions\QueryFailedException;
use Squid\Exceptions\SquidException;


/**
 * @method \PDOStatement execute()
 * @method string __toString()
 * @see \Squid\MySql\Command\IQuery
 */
trait TQuery 
{
	/**
	 * @inheritdoc
	 */
	public function queryAll($isAssoc = false) 
	{
		$result = $this->execute();
		
		if (!$result) return false;
		
		return $result->fetchAll($this->resolveFetchMode($isAssoc));
	}
	
	/**
	 * @inheritdoc
	 * @throws SquidException
	 */
	public function queryRow($isAssoc = false, $oneOrNone = true) {
		$result = $this->execute();
		
		if (!$result)
		{
			return false;
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
			return false;
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
			return false;
		
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
		
		if (!$result)
			throw new QueryFailedException();
		
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
	 * @inheritdoc
	 */
	private function resolveFetchMode($fetchMode) 
	{
		if ($fetchMode === true)		return \PDO::FETCH_ASSOC; 
		else if ($fetchMode === false)	return \PDO::FETCH_NUM;
		
		return $fetchMode;
	}
}