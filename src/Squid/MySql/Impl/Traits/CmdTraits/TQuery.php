<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\MySql\Utils\QueryFailedException;


/**
 * Implementation for the IQuert methods. Note that it does not implement queryExists and queryCount functions!
 * This trait uses the execute() command.
 * @todo fix the return type of execute. Should be PDO something.
 * @todo Add assembly and bindParams as method. 
 * @method mixed execute()
 */
trait TQuery {
	
	/**
	 * Get all items in the requested set.
	 * @param bool|int $isAssoc If true return assoc result; otherwise false. Also
	 * int, representing \PDO::FETCH_* mode can be passed for other mods.
	 * @return array Array of fields, empty array if none selected, false for error.
	 * Each element in the array is the array of the column values.
	 */
	public function queryAll($isAssoc = false) {
		$result = $this->execute();
		
		if (!$result) {
			return false;
		}
		
		return $result->fetchAll($this->resolveFetchMode($isAssoc));
	}
	
	/**
	 * Get a single row from the given query
	 * @param bool|int $isAssoc If true return assoc result; otherwise false. Also
	 * int, representing \PDO::FETCH_* mode can be passed for other mods.
	 * @param bool $oneOrNone If true, and more than one row selected, error is thrown. Otherwise
	 * only the first row from the set is returned.
	 * @return array|bool Single row or false on failer.
	 */
	public function queryRow($isAssoc = false, $oneOrNone = true) {
		$result = $this->execute();
		
		if (!$result) {
			return false;
		}
		
		if ($oneOrNone && $result->rowCount() > 1) {
			throw new \Exception('More than one row was selected! Command: ' . $this->assemble() .
				', bind: ["' . implode('", "', $this->bindParams()) . '"]');
		}
		
		return $result->fetch($this->resolveFetchMode($isAssoc));
	}
	
	/**
	 * Select single row as assocative array.s
	 * @param bool $oneOrNone If true, and more than one row selected, error is thrown. Otherwise
	 * only the first row from the set is returned.
	 * @return array|bool Single row or false on failer.
	 */
	public function queryRowAssoc($oneOrNone = true) {
		return $this->queryRow(true, $oneOrNone);
	}
	
	/**
	 * Select the first column of all rows.
	 * @param bool $oneOrNone If true, and more than one column selected, error is thrown. Otherwise
	 * only the first column from the set is returned.
	 * @return array|bool False on failer; otherwise array contianing all the values of the first column
	 * retaining the selected rows order. If none selected, empty array is returned.
	 */
	public function queryColumn($oneOrNone = true) {
		$result = $this->execute();
		$data = [];
		
		if (!$result) {
			return false;
		}
		
		if ($oneOrNone && $result->columnCount() > 1) {
			throw new \Exception('More than one column was selected!');
		}
		
		while ($row = $result->fetch(\PDO::FETCH_NUM)) {
			$data[] = $row[0];
		}
		
		return $data;
	}
	
	/**
	 * Select the first column of the first row.
	 * @param mixed $default Value to return on error.
	 * @param bool $expectOne If true, and not exactly one column/row selected, 
	 * error is thrown; otherwise only the first column of the first row, from the set is returned.
	 * @return mixed|bool Column value or false on failer.
	 */
	public function queryScalar($default = false, $expectOne = true) {
		$result = $this->execute();
		
		if (!$result) {
			return $default;
		}
		
		if ($expectOne && $result->rowCount() != 1 && $result->columnCount() != 1) {
			throw new \Exception('More than one column or row was selected!');
		} else if ($result->rowCount() == 0) {
			return $default;
		}
		
		return $result->fetch(\PDO::FETCH_NUM)[0];
	}
	
	/**
	 * Execute a query and return an integer value of the first column/row.
	 * @param bool $expectOne If true, and not exactly one column/row selected, error is thrown; 
	 * otherwise only the first column of the first row, from the set is returned.
	 * @return int|bool Integer value or false on failer.
	 */
	public function queryInt($expectOne = true) {
		$result = $this->queryScalar(false, $expectOne);
		
		return ($result === false ? false : (int)$result);
	}
	
	/**
	 * @param callable $callback Called for each selected row.
	 * @param bool $isAssoc
	 * @return bool
	 */
	public function queryWithCallback($callback, $isAssoc = true) {
		$fetchMode = $this->resolveFetchMode($isAssoc);
		$result = $this->execute();
		
		if (!$result) {
			return false;
		}
		
		while ($row = $result->fetch($fetchMode)) {
			$value = call_user_func($callback, $row);
			
			if ($value === false) {
				return false;
			} else if ($value === 0) {
				break;
			}
		}
		
		return true;
	}
	
	/**
	 * @param bool $isAssoc
	 * @return \Iterator
	 * @throws QueryFailedException
	 */
	public function queryIterator($isAssoc = true) {
		$fetchMode = $this->resolveFetchMode($isAssoc);
		$result = $this->execute();
		
		if (!$result) {
			throw new QueryFailedException();
		}
		
		try {
			while ($row = $result->fetch($fetchMode)) {
				yield $row;
			}
			
		// Free resources when generator released before reaching the end of the iteration.
		} finally {
			$result->closeCursor();
		}
	}
	
	public function debug() {
		var_dump($this->assemble(), $this->bindParams());
	}
	
	public function debugDie() {
		var_dump($this->assemble(), $this->bindParams());
		die;
	}
	
	
	/**
	 *@param bool|int $fetchMode False of numeric, true for associative,
	 * otherwise the requested mode.
	 * @return int
	 */
	private function resolveFetchMode($fetchMode) {
		if ($fetchMode === true) {
			return \PDO::FETCH_ASSOC; 
		} else if ($fetchMode === false) {
			return \PDO::FETCH_NUM; 
		}
		
		return $fetchMode;
	}
}