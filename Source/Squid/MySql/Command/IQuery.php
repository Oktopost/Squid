<?php
namespace Squid\MySql\Command;


use Objection\LiteObject;
use Structura\Map;


interface IQuery
{
	/**
	 * Query associative result set.
	 * @return array
	 */
	public function query();
	
	/**
	 * Query numeric result set.
	 * @return array|false
	 */
	public function queryNumeric();
	
	/**
	 * Query the entire result set using defined fetch method. 
	 * @param bool|int $isAssoc Will accept \PDO::FETCH_*
	 * @return array|false
	 */
	public function queryAll($isAssoc = false);
	
	/**
	 * @param bool|int $isAssoc Will accept \PDO::FETCH_*
	 * @param bool $failOnMultipleResults
	 * @return ?array
	 */
	public function queryRow($isAssoc = false, bool $failOnMultipleResults = true);
	
	/**
	 * @param bool $failOnMultipleResults If true and more then one column is selected by the query, throw an exception.
	 * @return array|bool Numeric array of all the values in the first found row.
	 */
	public function queryColumn($failOnMultipleResults = true);
	
	public function queryScalar($default = null, bool $failOnMultipleResults = true);
	public function queryInt(?int $default = null, bool $failOnMultipleResults = true): ?int;
	public function queryFloat(?float $default = null, bool $failOnMultipleResults = true): ?float;
	public function queryBool(?bool $default = null, bool $failOnMultipleResults = true): ?bool;
	
	/**
	 * Execute a SELECT EXISTS (Current query)
	 * @return bool|null Null on error
	 */
	public function queryExists();
	
	/**
	 * Execute SELECT COUNT(*). If the query is a group by query, number of distinct values is returned.
	 * @return int|bool
	 */
	public function queryCount();
	
	/**
	 * @param callable $callback Called for each selected row. 
	 * If callback returns false, queryWithCallback will abort and return false.
	 * If callback returns 0, queryWithCallback will abort and return true.
	 * For any other value, callback will continue to the next row.
	 * If no rows selected at all, queryWithCallback will still return true.
	 * @param bool $isAssoc
	 * @return bool
	 */
	public function queryWithCallback($callback, $isAssoc = true);
	
	/**
	 * Return an iterator to iterate over all found rows.
	 * @param bool $isAssoc If true return assoc result; otherwise return numeric.
	 * Also integer value as \PDO::FETCH_* mode can be passed for other results.
	 * @return \Iterator
	 */
	public function queryIterator($isAssoc = true);
	
	/**
	 *  Return an iterator to iterate over all found rows.
	 *  Each iteration will contain an array of rows instead of a single raw.
	 * @param bool $isAssoc
	 * @param int $size
	 * @return \Iterator
	 */
	public function queryIteratorBulk(int $size = 100, $isAssoc = true);
	
	/**
	 * Return an array where the result of one column is the index and the second is value.
	 * @param int|string $key Name of the key column.
	 * @param int|string $value Name of the value column
	 * @return array|false
	 */
	public function queryMap($key = 0, $value = 1);
	
	/**
	 * @param string $className LiteObject class name.
	 * @return LiteObject|null
	 */
	public function queryObject(string $className): ?LiteObject;
	
	/**
	 * @param string $className LiteObject class name.
	 * @return LiteObject[]
	 */
	public function queryObjects(string $className): array;
	
	/**
	 * Return array where each value is an array of rows grouped by a single column.
	 * @param string|int $byColumn Column to group by.
	 * @param bool $removeColumn If set to true, the group by column is removed from the row.
	 * @return Map
	 */
	public function queryGroupBy($byColumn, bool $removeColumn = false): Map;
	
	/**
	 * Return an array where the result of one column is the index and the remaining data is value.
	 * @param int|string $key Name of the key column.
	 * @param bool $removeColumnFromRow Should remove the key column from values.
	 * @return array|false
	 */
	public function queryMapRow($key = 0, $removeColumnFromRow = false);
}