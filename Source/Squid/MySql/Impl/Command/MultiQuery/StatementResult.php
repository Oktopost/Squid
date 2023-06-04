<?php
namespace Squid\MySql\Impl\Command\MultiQuery;


use Structura\Map;
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
	 * Query numeric result set.
	 * @return array|false
	 */
	public function queryNumeric()
	{
		// TODO: Implement queryNumeric() method.
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
	 * @param bool $failOnMultipleResults
	 * @return array|false
	 */
	public function queryRow($isAssoc = false, bool $failOnMultipleResults = true)
	{
		// TODO: Implement queryRow() method.
	}
	
	/**
	 * @param bool $failOnMultipleResults If true and more then one column is selected by the query, throw an exception.
	 * @return array|bool Numeric array of all the values in the first found row.
	 */
	public function queryColumn(bool $failOnMultipleResults = true)
	{
		// TODO: Implement queryColumn() method.
	}
	
	/**
	 * @param mixed $default Default value to return if no results found.
	 * @param bool $failOnMultipleResults If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return mixed First column of the first row, or $default value if nothing found.
	 */
	public function queryScalar($default = null, bool $failOnMultipleResults = true)
	{
		// TODO: Implement queryScalar() method.
	}
	
	/**
	 * @param bool $failOnMultipleResults If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return int|bool False on error.
	 */
	public function queryInt(?int $default = null, bool $failOnMultipleResults = true): ?int
	{
		// TODO: Implement queryInt() method.
	}
	
	/**
	 * @param bool $failOnMultipleResults If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return int|bool False on error.
	 */
	public function queryFloat(?float $default = null, bool $failOnMultipleResults = true): ?float
	{
		// TODO: Implement queryInt() method.
	}
	
	/**
	 * @param bool $failOnMultipleResults If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return bool|null Null on error.
	 */
	public function queryBool(?bool $default = null, bool $failOnMultipleResults = true): ?bool
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
	public function queryWithCallback(callable $callback, ?array &$result = null, bool $isAssoc = true)
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
	 *  Return an iterator to iterate over all found rows.
	 *  Each iteration will contain an array of rows instead of a single raw.
	 * @param bool $isAssoc
	 * @param int $size
	 * @return \Iterator
	 */
	public function queryIteratorBulk(int $size = 100, $isAssoc = true)
	{
		// TODO: Implement queryIteratorBulk() method.
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
	
	public function queryObject(string $className)
	{
		// TODO: Implement queryObject() method.
	}
	
	public function queryObjects(string $className): array
	{
		// TODO: Implement queryObjects() method.
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
	
	/**
	 * @param string|int $byColumn
	 * @param bool $removeColumn
	 * @return Map
	 */
	public function queryGroupBy($byColumn, bool $removeColumn = false): Map
	{
		// TODO: Implement queryGroupBy() method.
	}
	
	public function queryValuesMap($key = 0, $value = 1, bool $useMap = false)
	{
		// TODO: Implement queryValuesMap() method.
	}
	
	public function queryValuesGroup($key = 0, $value = 1, bool $useMap = false)
	{
		// TODO: Implement queryValuesGroup() method.
	}
	
	public function queryRecordsMap($key = 0, bool $excludeKey = false, bool $useMap = false)
	{
		// TODO: Implement queryRecordsMap() method.
	}
	
	public function queryRecordsGroup($key = 0, bool $excludeKey = false, bool $useMap = false)
	{
		// TODO: Implement queryRecordsGroup() method.
	}
}