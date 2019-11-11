<?php
namespace Squid\MySql\Extensions\Enrichment;


use Objection\LiteObject;
use Structura\Map;
use Squid\MySql\Command\IQuery;


abstract class AbstractQueryEnrichment implements IQueryEnrichment
{
	/** @var IQuery */
	private $source;
	
	
	/**
	 * @return IQuery
	 */
	protected function getSource()
	{
		return $this->source;
	}
	
	
	/**
	 * @param IQuery $query
	 * @return static
	 */
	public function setSource(IQuery $query)
	{
		$this->source = $query;
		return $this;
	}
	
	
	/**
	 * @param bool|int $isAssoc Will accept \PDO::FETCH_*
	 * @return array
	 */
	public function queryAll($isAssoc = false)
	{
		return $this->source->queryAll($isAssoc);
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
	 * Query numeric result set.
	 * @return array|false
	 */
	public function queryNumeric()
	{
		return $this->source->queryNumeric();
	}
	
	/**
	 * @param bool|int $isAssoc Will accept \PDO::FETCH_*
	 * @param bool $failOnMultipleResults
	 * @return array|false
	 */
	public function queryRow($isAssoc = false, bool $failOnMultipleResults = true)
	{
		return $this->source->queryRow($isAssoc, $failOnMultipleResults);
	}
	
	/**
	 * @param bool $failOnMultipleResults If true and more then one column is selected by the query, throw an exception.
	 * @return array|bool Numeric array of all the values in the first found row.
	 */
	public function queryColumn(bool $failOnMultipleResults = true)
	{
		return $this->source->queryColumn($failOnMultipleResults);
	}
	
	/**
	 * @param mixed $default Default value to return if no results found.
	 * @param bool $failOnMultipleResults If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return mixed First column of the first row, or $default value if nothing found.
	 */
	public function queryScalar($default = null, bool $failOnMultipleResults = true)
	{
		return $this->source->queryScalar($default, $failOnMultipleResults);
	}
	
	/**
	 * @param bool $failOnMultipleResults If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return int|bool False on error.
	 */
	public function queryInt(?int $default = null, bool $failOnMultipleResults = true): ?int
	{
		return $this->source->queryInt($failOnMultipleResults);
	}
	
	public function queryFloat(?float $default = null, bool $failOnMultipleResults = true): ?float
	{
		return $this->source->queryFloat($failOnMultipleResults);
	}
	
	/**
	 * @param bool $failOnMultipleResults If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return bool|null Null on error.
	 */
	public function queryBool(?bool $default = null, bool $failOnMultipleResults = true): ?bool
	{
		return $this->source->queryBool($failOnMultipleResults);
	}
	
	/**
	 * Execute a SELECT EXISTS (Current query)
	 * @return bool|null Null on error
	 */
	public function queryExists()
	{
		return $this->source->queryExists();
	}
	
	/**
	 * Execute SELECT COUNT(*). If the query is a group by query, number of distinct values is returned.
	 * @return int|bool
	 */
	public function queryCount()
	{
		return $this->source->queryCount();
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
		return $this->source->queryWithCallback($callback, $isAssoc);
	}
	
	/**
	 * Return an iterator to iterate over all found rows.
	 * @param bool $isAssoc If true return assoc result; otherwise return numeric.
	 * Also integer value as \PDO::FETCH_* mode can be passed for other results.
	 * @return \Iterator
	 */
	public function queryIterator($isAssoc = true)
	{
		return $this->source->queryIterator($isAssoc);
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
		return $this->source->queryIteratorBulk($isAssoc, $size);
	}
	
	/**
	 * Return an array where the result of one column is the index and the second is value.
	 * @param int|string $key Name of the key column.
	 * @param int|string $value Name of the value column
	 * @return array|false
	 */
	public function queryMap($key = 0, $value = 1)
	{
		return $this->source->queryMap($key, $value);
	}
	
	/**
	 * Return array where each value is an array of rows grouped by a single column.
	 * @param string|int $byColumn Column to group by.
	 * @param bool $removeColumn If set to true, the group by column is removed from the row.
	 * @return Map
	 */
	public function queryGroupBy($byColumn, bool $removeColumn = false): Map
	{
		return $this->source->queryGroupBy($byColumn, $removeColumn);
	}
	
	/**
	 * Return an array where the result of one column is the index and the remaining data is value.
	 * @param int|string $key Name of the key column.
	 * @param bool $removeColumnFromRow Should remove the key column from values.
	 * @return array|false
	 */
	public function queryMapRow($key = 0, $removeColumnFromRow = false)
	{
		return $this->source->queryMapRow($key, $removeColumnFromRow);
	}
	
	public function queryObject(string $className)
	{
		return $this->source->queryObject($className);
	}
	
	public function queryObjects(string $className): array
	{
		return $this->source->queryObjects($className);
	}
}