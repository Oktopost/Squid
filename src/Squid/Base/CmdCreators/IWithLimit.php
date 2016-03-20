<?php
namespace Squid\Base\CmdCreators;


use \Squid\Consts\OrderBy;


/**
 * Queries that have the limit option.
 */
interface IWithLimit {
	
	
	/**
	 * @param int $from Zero based index.
	 * @param int $count
	 * @return static
	 */
	public function limit($from, $count);
	
	/**
	 * Set as limit but with $from always equals to zero.
	 * @param int $count Maximum number of rows to select.
	 * @return static
	 */
	public function limitBy($count);
	
	/**
	 * Use limit statement for a page expression.
	 * @param int $page Zero based index of the page to select.
	 * @param int $pageSize Number of elements per page.
	 * @return static
	 */
	public function page($page, $pageSize);
	
	/**
	 * Add order by fields.
	 * @param string|array $column Single column, expression or array of columns.
	 * @param int $type Order type. Use OrderBy consts. Either single value, or array of 
	 * values. In the later, $column must be of same size.
	 * @return static
	 */
	public function orderBy($column, $type = OrderBy::ASC);
}