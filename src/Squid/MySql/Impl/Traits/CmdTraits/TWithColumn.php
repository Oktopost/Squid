<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


trait TWithColumn
{
	/**
	 * @param array $columns
	 * @return static
	 */
	public function column(...$columns)
	{
		return $this->addColumn($columns, false);
	}
	
	/**
	 * @param string|array $columns
	 * @param string|bool $table
	 * @return static
	 */
	public function columns($columns, $table = false)
	{
		if (!is_array($columns)) $columns = [$columns];
		
		foreach ($columns as &$column) 
		{
			$column = "`$table`.`$column`";
		}
		
		return $this->addColumn($columns, false);
	}
	
	/**
	 * @param string|array $columns
	 * @param bool|array $bind
	 * @return static
	 */
	public function columnsExp($columns, $bind = false) 
	{
		if (!is_array($columns)) $columns = [$columns];
		
		return $this->addColumn($columns, $bind);
	}
	
	/**
	 * @param string $column
	 * @param string $alias
	 * @return static
	 */
	public function columnAs($column, $alias) 
	{
		return $this->addColumn(["$column as $alias"], false);
	}
	
	/**
	 * @param string $column
	 * @param string $alias
	 * @param array|bool $bind
	 * @return static
	 */
	public function columnAsExp($column, $alias, $bind = false) 
	{
		return $this->addColumn(["$column as $alias"], []);
	}
}