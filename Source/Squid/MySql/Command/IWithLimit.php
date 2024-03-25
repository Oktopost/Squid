<?php
namespace Squid\MySql\Command;


use Squid\OrderBy;


interface IWithLimit
{
	/**
	 * @param int $from
	 * @param int $count
	 * @return static
	 */
	public function limit($from, $count);
	
	/**
	 * @param int $count
	 * @return static
	 */
	public function limitBy($count);
	
	/**
	 * @param int $page
	 * @param int $pageSize
	 * @return static
	 */
	public function page($page, $pageSize);
	
	/**
	 * @param string|array $column
	 * @param int $type
	 * @return static
	 */
	public function orderBy($column, $type = OrderBy::ASC);
	
	/**
	 * @param string|string[] $column
	 * @return static
	 */
	public function orderByAsc($column);
	
	/**
	 * @param string|string[] $column
	 * @return static
	 */
	public function orderByDesc($column);
}