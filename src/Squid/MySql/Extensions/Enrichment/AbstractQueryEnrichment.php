<?php
namespace Squid\MySql\Extensions\Enrichment;


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
	 * @param bool|int $isAssoc Will accept \PDO::FETCH_*
	 * @param bool $expectOne
	 * @return array|false
	 */
	public function queryRow($isAssoc = false, $expectOne = true)
	{
		return $this->source->queryRow($isAssoc, $expectOne);
	}
	
	/**
	 * @param bool $expectOne If true and more then one column is selected by the query, throw an exception.
	 * @return array|bool Numeric array of all the values in the first found row.
	 */
	public function queryColumn($expectOne = true)
	{
		return $this->source->queryColumn($expectOne);
	}
	
	/**
	 * @param mixed $default Default value to return if no results found.
	 * @param bool $expectOne If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return mixed First column of the first row, or $default value if nothing found.
	 */
	public function queryScalar($default = false, $expectOne = true)
	{
		return $this->source->queryScalar($default, $expectOne);
	}
	
	/**
	 * @param bool $expectOne If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return int|bool False on error.
	 */
	public function queryInt($expectOne = true)
	{
		return $this->source->queryInt($expectOne);
	}
	
	/**
	 * @param bool $expectOne If true and more then one column or row are
	 * selected by the query, throw an exception.
	 * @return bool|null Null on error.
	 */
	public function queryBool($expectOne = true)
	{
		return $this->source->queryBool($expectOne);
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
	public function queryWithCallback($callback, $isAssoc = true)
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
	 * Return an array where the result of one column is the index and the second is value.
	 * @param int|string $key Name of the key column.
	 * @param int|string $value Name of the value column
	 * @return array|false
	 */
	public function queryMap($key = 0, $value = 1)
	{
		return $this->source->queryMap($key, $value);
	}
}