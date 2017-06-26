<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\OrderBy;


/**
 * Implements calculation behavior for the IWithLimit interface. Relies on the using class
 * to implement methods limit($from, $count), and _orderBy(array $expressions).
 * 
 * @method mixed limit(int $from, int $count)
 * @method mixed _orderBy(array $expressions)
 * @mixin \Squid\MySql\Command\IWithLimit
 */
trait TWithLimit 
{
	/**
	 * Append the DESC keyword to all requested keywords or expressions.
	 * @param string|array $column
	 */
	private function appendDesc(&$column) 
	{
		if (is_array($column)) 
		{
			foreach ($column as &$col) 
			{
				$col = "$col DESC";
			}
		}
		else 
		{
			$column = ["$column DESC"];
		}
	}
	
	
	/**
	 * Set as limit but with $from always equals to zero.
	 * @param int $count Maximum number of rows to select.
	 * @return static
	 */
	public function limitBy($count) 
	{
		return $this->limit(0, $count);
	}
	
	/**
	 * Use limit statement for a page expression.
	 * @param int $page Zero based index of the page to select.
	 * @param int $pageSize Number of elements per page.
	 * @return static
	 */
	public function page($page, $pageSize) 
	{
		return $this->limit($page * $pageSize, $pageSize);
	}
	
	/**
	 * Add order by fields.
	 * @param string|array $column Single column, expression or array of columns.
	 * @param int $type Order type. Use OrderBy consts. Either single value, or array of 
	 * values. In the later, $column must be of same size.
	 * @return static
	 */
	public function orderBy($column, $type = OrderBy::ASC) 
	{
		if ($type == OrderBy::DESC) 
		{
			$this->appendDesc($column);
		} 
		else if (!is_array($column)) 
		{
			$column = [$column];
		}
		
		return $this->_orderBy($column);
	}
}