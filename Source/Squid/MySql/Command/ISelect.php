<?php
namespace Squid\MySql\Command;


interface ISelect extends IWithWhere, IWithExtendedWhere, IWithLimit, IWithColumns
{
	/**
	 * @param bool $distinct
	 * @return static
	 */
	public function distinct(bool $distinct = true);
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string|null $alias
	 * @param bool $escape
	 * @return static
	 */
	public function from($table, ?string $alias = null, bool $escape = true);
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @return static
	 */
	public function join($table, string $alias, string $condition, $bind = []);
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @param bool $outer
	 * @return static
	 */
	public function leftJoin($table, string $alias, string $condition, $bind = [], bool $outer = false);
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @param bool $outer
	 * @return static
	 */
	public function rightJoin($table, string $alias, string $condition, $bind = [], bool $outer = false);


	/**
	 * @param string|array $column
	 * @param array|mixed $bind
	 * @return static
	 */
	public function groupBy($column, $bind = []);
	
	/**
	 * @param bool $withRollup
	 * @return static
	 */
	public function withRollup(bool $withRollup = true);

	/**
	 * @param string $exp
	 * @param array|mixed $bind
	 * @return static
	 */
	public function having(string $exp, $bind = []);
	
	/**
	 * @param IMySqlCommandConstructor $select
	 * @param bool $all
	 * @return static
	 */
	public function union(IMySqlCommandConstructor $select, bool $all = false);
	
	/**
	 * @param IMySqlCommandConstructor $select
	 * @return static
	 */
	public function unionAll(IMySqlCommandConstructor $select);
	
	/**
	 * @param bool $forUpdate
	 * @return static
	 */
	public function forUpdate(bool $forUpdate = true);
	
	/**
	 * @param bool $lockInShareMode
	 * @return static
	 */
	public function lockInShareMode(bool $lockInShareMode = true);
	
	
	public function __clone();
	public function __toString();
}