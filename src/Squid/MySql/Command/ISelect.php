<?php
namespace Squid\MySql\Command;


interface ISelect extends IWithWhere, IWithExtendedWhere, IWithLimit, IWithColumns
{
	/**
	 * @param bool $distinct 
	 * @return static
	 */
	public function distinct($distinct = true);
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string|bool $alias
	 * @return static
	 */
	public function from($table, $alias = false);
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @return static
	 */
	public function join($table, $alias, $condition, $bind = false);
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @param bool $outer
	 * @return static
	 */
	public function leftJoin($table, $alias, $condition, $bind = false, $outer = false);
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @param bool $outer
	 * @return static
	 */
	public function rightJoin($table, $alias, $condition, $bind = false, $outer = false);


	/**
	 * @param string|array $column
	 * @param array|bool $bind
	 * @return static
	 */
	public function groupBy($column, $bind = false);
	
	/**
	 * @param string $exp
	 * @param mixed|array|bool $bind
	 * @return static
	 */
	
	/**
	 * @param bool $withRollup
	 * @return static
	 */
	public function withRollup($withRollup = true);
	public function having($exp, $bind = false);
	
	/**
	 * @param IMySqlCommandConstructor $select
	 * @param bool $all
	 * @return static
	 */
	public function union(IMySqlCommandConstructor $select, $all = false);
	
	/**
	 * @param IMySqlCommandConstructor $select
	 * @return static
	 */
	public function unionAll(IMySqlCommandConstructor $select);
	
	/**
	 * @param bool $forUpdate
	 * @return static
	 */
	public function forUpdate($forUpdate = true);
	
	/**
	 * @param bool $lockInShareMode
	 * @return static
	 */
	public function lockInShareMode($lockInShareMode = true);
	
	
	public function __clone();
	public function __toString();
}