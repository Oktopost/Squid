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
	public function queryColumn(bool $failOnMultipleResults = true);
	
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
	 * Execute SELECT COUNT(*).
	 * @return int|bool
	 */
	public function queryCount();
	
	/**
	 * @param callable $callback Called for each selected row.
	 * If callback returns false, queryWithCallback will abort and return false.
	 * If callback returns 0, queryWithCallback will abort and return true.
	 * For any other value, callback will continue to the next row.
	 * If no rows selected at all, queryWithCallback will still return true.
	 * @param array|null $result Array of non scalar values returned by the callback.
	 * @param bool $isAssoc
	 * @return bool
	 */
	public function queryWithCallback(callable $callback, bool $isAssoc = true, ?array &$result = null);
	
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
	 * @param string $className LiteObject class name.
	 * @return LiteObject|null
	 */
	public function queryObject(string $className);
	
	/**
	 * @param string $className LiteObject class name.
	 * @return LiteObject[]
	 */
	public function queryObjects(string $className): array;
	
	/**
	 * @param int $key
	 * @param int $value
	 * @param bool $useMap
	 * @return array|Map
	 */
	public function queryValuesMap($key = 0, $value = 1, bool $useMap = false);
	
	/**
	 * @param int $key
	 * @param int $value
	 * @param bool $useMap
	 * @return array|Map
	 */
	public function queryValuesGroup($key = 0, $value = 1, bool $useMap = false);
	
	/**
	 * @param int $key
	 * @param bool $excludeKey
	 * @param bool $useMap
	 * @return array|Map
	 */
	public function queryRecordsMap($key = 0, bool $excludeKey = false, bool $useMap = false);
	
	/**
	 * @param int $key
	 * @param bool $excludeKey
	 * @param bool $useMap
	 * @return array|Map
	 */
	public function queryRecordsGroup($key = 0, bool $excludeKey = false, bool $useMap = false);
	
	
	/**
	 * @deprecated 
	 */
	public function queryMap($key = 0, $value = 1);
	
	/**
	 * @deprecated 
	 */
	public function queryGroupBy($byColumn, bool $removeColumn = false): Map;
	
	/**
	 * @deprecated 
	 */
	public function queryMapRow($key = 0, $removeColumnFromRow = false);
}