<?php
namespace Squid\MySql\Connectors\Objects\Query;


use Squid\MySql\Command\ICmdSelect;
use Structura\Map;


interface IObjectSelector
{
	/**
	 * @param ICmdSelect $select
	 * @return array|false
	 */
	public function all(ICmdSelect $select);
	
	/**
	 * @param ICmdSelect $select
	 * @return mixed|false
	 */
	public function one(ICmdSelect $select);
	
	/**
	 * @param ICmdSelect $select
	 * @return mixed|false
	 */
	public function first(ICmdSelect $select);

	/**
	 * @param ICmdSelect $select
	 * @param callable $callback
	 * @return bool
	 */
	public function withCallback(ICmdSelect $select, callable $callback): bool;

	/**
	 * @param ICmdSelect $select
	 * @return iterable
	 */
	public function iterator(ICmdSelect $select): iterable;

	/**
	 * @param ICmdSelect $select
	 * @param string $field
	 * @param bool $removeColumnFromRow
	 * @return array|false
	 */
	public function map(ICmdSelect $select, string $field, bool $removeColumnFromRow = false);
	
	/**
	 * @param ICmdSelect $select
	 * @param string|int $byColumn
	 * @param bool $removeColumn
	 * @return Map
	 */
	public function groupBy(ICmdSelect $select, $byColumn, bool $removeColumn = false): Map;
}