<?php
namespace Squid\Traits\CmdTraits;


use \Squid\OrderBy;


/**
 * Implements calculation behavior for the IWithLimit interface. Relies on the using class
 * to implement methods limit($from, $count), and _orderBy(array $expressions).
 * 
 * @method mixed limit(int $from, int $count)
 * @method mixed _orderBy(array $expressions)
 */
trait TWithLimit {
	
	public function limitBy($count) {
		return $this->limit(0, $count);
	}
	
	public function page($page, $pageSize) {
		return $this->limit($page * $pageSize, $pageSize);
	}
	
	/**
	 * Add order by fields.
	 * @param string|array $column Single column, expression or array of columns.
	 * @param int $type Order type. Use OrderBy consts. Either single value, or array of 
	 * values. In the later, $column must be of same size.
	 */
	public function orderBy($column, $type = OrderBy::ASC) {
		if ($type == OrderBy::DESC) {
			$this->appendDesc($column);
		} else if (!is_array($column)) {
			$column = array($column);
		}
		
		return $this->_orderBy($column);
	}
	
	
	/**
	 * Append the DESC keyword to all requested keywords or expressions.
	 * @param string|array $column Single column, expression or array of columns.
	 */
	private function appendDesc(&$column) {
		if (is_array($column)) {
			foreach ($column as &$col) {
				$col = "$col DESC";
			}
		} else {
			$column = array("$column DESC");
		}
	}
}