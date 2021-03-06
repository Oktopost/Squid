<?php
namespace Squid\MySql\Impl\Command;


use Squid\Exceptions\SquidException;


class Assembly 
{
	use \Traitor\TStaticClass;
	
	
	/**
	 * @param array $values Expressions of the where clause
	 * @param bool $forceExist If true, $values must have at least one value
	 * @return string
	 * @throws SquidException
	 */
	public static function appendWhere($values, $forceExist = false) 
	{
		if ($forceExist && !$values)
			throw new SquidException('WHERE clause must be present for this type of command!');
		
		return Assembly::append('WHERE', $values, ' AND ');
	}
	
	/**
	 * @param array $values Expressions of the order by clause
	 * @return string
	 */
	public static function appendOrderBy($values) 
	{
		return Assembly::append('ORDER BY', $values, ', ');
	}
	
	/**
	 * Append set clause.
	 * @param array $values Expressions of the set clause
	 * @param bool $forceExist If true, $values must have at least one value
	 * @return string
	 * @throws SquidException
	 */
	public static function appendSet($values, $forceExist = false)
	{
		if ($forceExist && !$values)
			throw new SquidException('SET clause must be present for this type of command!');
		
		return Assembly::append('SET', $values, ', ');
	}
	
	/**
	 * @param bool $limit True if limit requested, otherwise false.
	 * @param array $bind
	 * @return string
	 */
	public static function appendLimit($limit, $bind)
	{
		if (!$limit) return '';
		
		return ((!is_array($bind) || count($bind) == 1) ? 'LIMIT ? ' : 'LIMIT ?, ? ');
	}
	
	/**
	 * @param string $prefix
	 * @param array|bool $values
	 * @param string $glue
	 * @return string
	 */
	public static function append($prefix, $values, $glue = ',')
	{
		return ($values ? $prefix . ' ' . implode($glue, $values) . ' ' : '');
	}
	
	/**
	 * @param array $values
	 * @param string $glue
	 * @return string
	 */
	public static function appendDirectly(array $values, $glue)
	{
		return ($values ? implode($glue, $values) . ' ' : '');
	}
	
	/**
	 * @param int $count Number of elements.
	 * @param bool $surround If true, generate () at start and end of the result.
	 * @param string $glue
	 * @return string
	 */
	public static function placeholder($count, $surround = false, $glue = ',')
	{
		$result = implode($glue, array_pad(array(), $count, '?'));
		return ($surround ? "($result)" : $result);
	}
}