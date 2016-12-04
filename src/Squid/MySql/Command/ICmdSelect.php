<?php
namespace Squid\MySql\Command;


/**
 * @see https://dev.mysql.com/doc/refman/5.7/en/select.html
 */
interface ICmdSelect extends IQuery, IMySqlCommand, IWithWhere, IWithLimit
{
	/**
	 * @param bool $distinct 
	 * @return static
	 */
	public function distinct($distinct = true);
	
	/**
	 * @param array $columns
	 * @return static
	 */
	public function column(...$columns);
	
	/**
	 * @param string|array $columns
	 * @param string|bool $table
	 * @return static
	 */
	public function columns($columns, $table = false);
	
	/**
	 * @param string|array $columns
	 * @param bool|array $bind
	 * @return static
	 */
	public function columnsExp($columns, $bind = false);
	
	/**
	 * @param string $column
	 * @param string $alias
	 * @return static
	 */
	public function columnAs($column, $alias);
	
	/**
	 * @param string $column
	 * @param string $alias
	 * @param array|bool $bind
	 * @return static
	 */
	public function columnAsExp($column, $alias, $bind = false);
	
	/**
	 * @param string $table
	 * @param string|bool $alias
	 * @return static
	 */
	public function from($table, $alias = false);
	
	/**
	 * @param string|ICmdSelect $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @return static
	 */
	public function join($table, $alias, $condition, $bind = false);
	
	/**
	 * @param string|ICmdSelect $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @param bool $outer
	 * @return static
	 */
	public function leftJoin($table, $alias, $condition, $bind = false, $outer = false);
	
	/**
	 * @param string|ICmdSelect $table
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
	public function having($exp, $bind = false);
	
	/**
	 * @param bool $withRollup
	 * @return static
	 */
	public function withRollup($withRollup = true);
	
	/**
	 * @param ICmdSelect $select
	 * @param bool $all
	 * @return static
	 */
	public function union(ICmdSelect $select, $all = false);
	
	/**
	 * @param ICmdSelect $select
	 * @return static
	 */
	public function unionAll(ICmdSelect $select);
	
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
}