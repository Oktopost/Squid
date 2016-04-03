<?php
namespace Squid\Base\Cmd;


use \Squid\Base\Utils\QueryFailedException;


/**
 * Select query command.
 */
interface IQuery
{
	/**
	 * @param bool|int $isAssoc If true return assoc result; otherwise return numeric.
	 * Also integer value as \PDO::FETCH_* mode can be passed for other results.
	 * @return array
	 */
	public function queryAll($isAssoc = false);
	
	/**
	 * @param bool|int $isAssoc If true return assoc result; otherwise return numeric.
	 * Also integer value as \PDO::FETCH_* mode can be passed for other results.
	 * @param bool $expectOne If true and more then one row is selected by the query,
	 * throw an exception.
	 * @return array|bool
	 */
	public function queryRow($isAssoc = false, $expectOne = true);
	
	/**
	 * @param bool $expectOne If true and more then one row is selected by the query,
	 * throw an exception.
	 * @return array|bool Assoc array of the first found row.
	 */
	public function queryRowAssoc($expectOne = true);
	
	/**
	 * @param bool $expectOne If true and more then one column is selected by the query,
	 * throw an exception.
	 * @return array|bool Numeric array of all the values in the first found row.
	 */
	public function queryColumn($expectOne = true);
	
	/**
	 * @param mixed $default Default value to return if no results found.
	 * @param bool $expectOne If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return mixed First column of the first row, or $default value if nothing found.
	 */
	public function queryScalar($default = false, $expectOne = true);
	
	/**
	 * Query scalar value and return as int.
	 * @param bool $expectOne If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return int|bool False on error.
	 */
	public function queryInt($expectOne = true);

	/**
	 * Query scalar value and return as boolean.
	 * @param bool $expectOne If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return bool|null Null on error.
	 */
	public function queryBool($expectOne = true);
	
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
	 * @param bool $isAssoc
	 * @return bool
	 */
	public function queryWithCallback($callback, $isAssoc = true);
	
	/**
	 * Return an iterator to iterate over all found rows.
	 * @param bool $isAssoc If true return assoc result; otherwise return numeric.
	 * Also integer value as \PDO::FETCH_* mode can be passed for other results.
	 * @return \Iterator
	 * @throws QueryFailedException
	 */
	public function queryIterator($isAssoc = true);
}