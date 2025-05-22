<?php
namespace Squid\MySql\Extensions\Combine\Select;


use Squid\OrderBy;
use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Command\IWithLimit;
use Squid\MySql\Command\IWithExtendedWhere;
use Squid\MySql\Command\IMySqlCommandConstructor;
use Squid\MySql\Connection\IMySqlConnection;

use Structura\Map;


class SelectCombiner implements ICmdSelect
{
	/** @var ICmdSelect[] */
	private $selects = [];
	
	
	/**
	 * @param string $function
	 * @param array ...$args
	 * @return static
	 */
	private function invokeOnAll($function, ...$args)
	{
		foreach ($this->selects as $select)
		{
			call_user_func([$select, $function], ...$args);
		}

		return $this;
	}
	
	/**
	 * @param string $function
	 * @param array ...$args
	 * @return mixed
	 */
	private function invokeOnUnion($function, ...$args)
	{
		$all = $this->unionCommands();
		return call_user_func([$all, $function], ...$args);
	}
	
	
	public function __clone()
	{
		$clonedSelects = [];

		foreach ($this->selects as $key => $select)
		{
			$clonedSelects[$key] = clone $select;
		}

		$this->selects = $clonedSelects;
	}
	
	/**
	 * @param ICmdSelect $select
	 */
	public function add(ICmdSelect $select)
	{
		$this->selects[] = $select;
	}
	
	/**
	 * @param string|int $key
	 * @param ICmdSelect $select
	 */
	public function addByKey($key, ICmdSelect $select)
	{
		$this->selects[$key] = $select;
	}
	
	/**
	 * @param string|int $key
	 * @return ICmdSelect
	 */
	public function getByKey($key)
	{
		return $this->selects[$key];
	}
	
	/**
	 * @param string|int $key
	 * @return ICmdSelect
	 */
	public function removeByKey($key)
	{
		$select = $this->selects[$key];
		unset($this->selects[$key]);
		return $select;
	}
	
	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return isset($this->selects[$key]);
	}
	
	public function unionCommands(): ?ICmdSelect
	{
		/** @var ICmdSelect|null $main */
		$main = null;

		foreach ($this->selects as $select)
		{
			if (is_null($main)) $main = clone $select;
			else $main->union($select);
		}

		return $main;
	}
	
	
	// Calls on each select
	
	public function distinct(bool $distinct = true) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function column(...$columns) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function columns($columns, $table = false) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function columnsExp($columns, $bind = []) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function columnAs($column, $alias) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function columnAsExp($column, $alias, $bind = []) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function from($table, ?string $alias = null, bool $escape = true) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function with(ICmdSelect $select, string $alias) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function join($table, string $alias, string $condition, $bind = [], bool $escape = true) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function leftJoin($table, string $alias, string $condition, $bind = [], bool $outer = false, bool $escape = true) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function rightJoin($table, string $alias, string $condition, $bind = [], bool $outer = false, bool $escape = true) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function groupBy($column, $bind = []) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function having(string $exp, $bind = []) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function withRollup(bool $withRollup = true) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function union(IMySqlCommandConstructor $select, bool $all = false) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function unionAll(IMySqlCommandConstructor $select) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function forUpdate(bool $forUpdate = true) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function lockInShareMode(bool $lockInShareMode = true) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function setConnection(IMySqlConnection $conn): void { $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function limit($from, $count): IWithLimit { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function limitBy($count): IWithLimit { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function page($page, $pageSize): IWithLimit { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function orderBy($column, $type = OrderBy::ASC): IWithLimit { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function orderByAsc($column): IWithLimit { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function orderByDesc($column): IWithLimit { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function byId($value) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function byField($field, $value) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function byFields($fields, $values = null) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function where(string $exp, $bind = []) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereIn($field, $values, $negate = false) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereNotIn($field, $values) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereExists(ICmdSelect $select, $negate = false) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereNotExists(ICmdSelect $select) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereLike(string $exp, $value, ?string $escapeChar = null) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereNotLike(string $exp, $value, ?string $escapeChar = null) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereContains(string $exp, $value, bool $negate = false) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereStartsWith(string $exp, $value, bool $negate = false) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereEndsWith(string $exp, $value, bool $negate = false) { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereBetween(string $field, $greater, $less): IWithExtendedWhere { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereNotEqual(string $field, $value): IWithExtendedWhere { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereLess(string $field, $value): IWithExtendedWhere { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereLessOrEqual(string $field, $value): IWithExtendedWhere { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereGreater(string $field, $value): IWithExtendedWhere { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	public function whereGreaterOrEqual(string $field, $value): IWithExtendedWhere { return $this->invokeOnAll(__FUNCTION__, ...func_get_args()); }
	
	
	// Calls on union of all selects
	
	public function bind(): array { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function assemble(): string { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function execute() { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function query() { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryNumeric() { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryAll($isAssoc = false) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryRow($isAssoc = false, bool $failOnMultipleResults = true) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryColumn(bool $failOnMultipleResults = true) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryScalar($default = null, bool $failOnMultipleResults = true) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryInt(?int $default = null, bool $failOnMultipleResults = true): ?int { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryFloat(?float $default = null, bool $failOnMultipleResults = true): ?float { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryBool(?bool $default = null, bool $failOnMultipleResults = true): ?bool { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryWithCallback(callable $callback, bool $isAssoc = true, ?array &$result = null) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryIterator($isAssoc = true) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryIteratorBulk(int $size = 100, $isAssoc = true) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryMap($key = 0, $value = 1) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryObject(string $className) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryObjects(string $className): array { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryMapRow($key = 0, $removeColumnFromRow = false) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryGroupBy($byColumn, bool $removeColumn = false): Map { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryCount() { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryValuesMap($key = 0, $value = 1, bool $useMap = false) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryValuesGroup($key = 0, $value = 1, bool $useMap = false) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryRecordsMap($key = 0, bool $excludeKey = false, bool $useMap = false) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	public function queryRecordsGroup($key = 0, bool $excludeKey = false, bool $useMap = false) { return $this->invokeOnUnion(__FUNCTION__, ...func_get_args()); }
	
	/**
	 * Execute a SELECT EXISTS On each query. Will stop when at least one query returns true.
	 * @return bool|null Null on error
	 */
	public function queryExists()
	{
		foreach ($this->selects as $select)
		{
			$result = $select->queryExists();

			if (is_null($result) || $result)
			{
				return $result;
			}
		}

		return false;
	}
	
	public function queryLimit(int $totalLimit, $isAssoc = true)
	{
		$result = [];
		
		foreach ($this->selects as $select)
		{
			$query = clone $select;
			$query->limitBy($totalLimit);
			
			$queryResult = $select->queryAll($isAssoc);
			$totalLimit -= count($queryResult);
			
			$result = array_merge($queryResult);
			
			if ($totalLimit <= 0)
				break;
		}
		
		return $result;
	}
	
	
	/**
	 * For debug only
	 * @return string Return string in format: "Query string : {json bind params}"
	 */
	public function __toString()
	{
		$result = 'Union of: ';

		foreach ($this->selects as $key => $select)
		{
			if ($result)
				$result .= ', ';

			$result .= "$key: [ {$select->__toString()} ]";
		}

		return $result;
	}
}