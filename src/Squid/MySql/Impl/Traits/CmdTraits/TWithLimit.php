<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\OrderBy;


/**
 * Implements calculation behavior for the IWithLimit interface. Relies on the using class
 * to implement methods limit($from, $count), and _orderBy(array $expressions).
 * 
 * @method mixed limit(int $from, int $count)
 * @method mixed _orderBy(array $expressions)
 * @see \Squid\MySql\Command\IWithLimit
 */
trait TWithLimit 
{
	/**
	 * @inheritdoc
	 */
	public function limitBy($count) 
	{
		return $this->limit(0, $count);
	}
	
	/**
	 * @inheritdoc
	 */
	public function page($page, $pageSize) 
	{
		return $this->limit($page * $pageSize, $pageSize);
	}
	
	/**
	 * @inheritdoc
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
}