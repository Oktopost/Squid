<?php
namespace Squid\MySql\Impl\Command\MultiQuery;


use Squid\MySql\Command\MultiQuery\IStatementResult;


class StatementResult implements IStatementResult
{
	public function __construct(\PDOStatement $statement)
	{
	}
	
	
	/**
	 * Identical to queryAll(true);
	 * @return array
	 */
	public function query()
	{
		// TODO: Implement query() method.
	}
	
	/**
	 * @param bool|int $isAssoc Will accept \PDO::FETCH_*
	 * @return array|false
	 */
	public function queryAll($isAssoc = false)
	{
		// TODO: Implement queryAll() method.
	}
	
	/**
	 * @param bool|int $isAssoc Will accept \PDO::FETCH_*
	 * @param bool $expectOne
	 * @return array|false
	 */
	public function queryRow($isAssoc = false, $expectOne = true)
	{
		// TODO: Implement queryRow() method.
	}
	
	/**
	 * @param bool $expectOne If true and more then one column is selected by the query, throw an exception.
	 * @return array|bool Numeric array of all the values in the first found row.
	 */
	public function queryColumn($expectOne = true)
	{
		// TODO: Implement queryColumn() method.
	}
	
	/**
	 * @param mixed $default Default value to return if no results found.
	 * @param bool $expectOne If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return mixed First column of the first row, or $default value if nothing found.
	 */
	public function queryScalar($default = false, $expectOne = true)
	{
		// TODO: Implement queryScalar() method.
	}
	
	/**
	 * @param bool $expectOne If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return int|bool False on error.
	 */
	public function queryInt($expectOne = true)
	{
		// TODO: Implement queryInt() method.
	}
	
	/**
	 * @param bool $expectOne If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return bool|null Null on error.
	 */
	public function queryBool($expectOne = true)
	{
		// TODO: Implement queryBool() method.
	}
	
	/**
	 * Execute a SELECT EXISTS (Current query)
	 * @return bool|null Null on error
	 */
	public function queryExists()
	{
		// TODO: Implement queryExists() method.
	}
	
	/**
	 * Execute SELECT COUNT(*). If the query is a group by query, number of distinct values is returned.
	 * @return int|bool
	 */
	public function queryCount()
	{
		// TODO: Implement queryCount() method.
	}
	
	/**
	 * @param callable $callback Called for each selected row.
	 * If callback returns false, queryWithCallback will abort and return false.
	 * If callback returns 0, queryWithCallback will abort and return true.
	 * For any other value, callback will continue to the next row.
	 * @param bool $isAssoc
	 * @return bool
	 */
	public function queryWithCallback($callback, $isAssoc = true)
	{
		// TODO: Implement queryWithCallback() method.
	}
	
	/**
	 * Return an iterator to iterate over all found rows.
	 * @param bool $isAssoc If true return assoc result; otherwise return numeric.
	 * Also integer value as \PDO::FETCH_* mode can be passed for other results.
	 * @return \Iterator
	 */
	public function queryIterator($isAssoc = true)
	{
		// TODO: Implement queryIterator() method.
	}
	
	/**
	 * Return an array where the result of one column is the index and the second is value.
	 * @param int|string $key Name of the key column.
	 * @param int|string $value Name of the value column
	 * @return array|false
	 */
	public function queryMap($key = 0, $value = 1)
	{
		// TODO: Implement queryMap() method.
	}
	
	/**
	 * Return an array where the result of one column is the index and the remaining data is value.
	 * @param int|string $key Name of the key column.
	 * @param bool $removeColumnFromRow Should remove the key column from values.
	 * @return array|false
	 */
	public function queryMapRow($key = 0, $removeColumnFromRow = false)
	{
		// TODO: Implement queryMapRow() method.
	}
	
	/**
	 * @return int
	 */
	public function rowsCount()
	{
		// TODO: Implement rowsCount() method.
	}
}