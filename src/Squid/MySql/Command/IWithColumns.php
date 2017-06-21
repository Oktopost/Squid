<?php
namespace Squid\MySql\Command;


interface IWithColumns
{
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
}