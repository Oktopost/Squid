<?php
namespace Squid\Cmd;


class Assembly {
	use \Objection\TStaticClass;
	
	
	/**
	 * Append where clause.
	 * @param array $values Expressions of the where clause
	 * @param bool $forceExist If true, $values must have at least one value,
	 * otherwise error is thrown.
	 * @return string Where Clause.
	 */
	public static function appendWhere($values, $forceExist = false) {
		if ($forceExist && !$values) {
			throw new \Exception('WHERE Caluse must be present for this type of command!');
		}
		
		return Assembly::append('WHERE', $values, ' AND ');
	}
	
	/**
	 * Append order by clause.
	 * @param array $values Expressions of the order by clause
	 * @param bool $forceExist If true, $values must have at least one value,
	 * otherwise error is thrown.
	 * @return string Order by Clause.
	 */
	public static function appendOrderBy($values) {
		return Assembly::append('ORDER BY', $values, ', ');
	}
	
	/**
	 * Append set clause.
	 * @param array $values Expressions of the set clause
	 * @param bool $forceExist If true, $values must have at least one value,
	 * otherwise error is thrown.
	 * @return string Set clause.
	 */
	public static function appendSet($values, $forceExist = false) {
		if ($forceExist && !$values) {
			throw new \Exception('SET Caluse must be present for this type of command!');
		}
		
		return Assembly::append('SET', $values, ', ');
	}
	
	/**
	 * Append and implode a set of values.
	 * @param string $prefix Command to append to the start.
	 * @param string $values Array of values, if any.
	 * @param string $glue Glue to use to implode the values.
	 */
	public static function append($prefix, $values, $glue = ',') {
		return ($values ? $prefix . ' ' . implode($glue, $values) . ' ' : '');
	}
	
	/**
	 * Append and implode a set of values withought prefix.
	 * @param string|array $values Array of values, if any or a single value.
	 * @param string $glue Glue to use to implode the values.
	 */
	public static function appendDirect($values, $glue) {
		return ($values ? implode($glue, $values) . ' ' : '');
	}
	
	/**
	 * Append the limit command.
	 * @param bool $limit True if limit requested, otherwise false.
	 * @return string String to append to the query.
	 */
	public static function appendLimit($limit, $limitBinds) {
		if (!$limit) {
			return '';
		}
		
		return (count($limitBinds) == 1 ? 'LIMIT ? ' : 'LIMIT ?, ? ');
	}
	
	/**
	 * Create a placeholders for given number of elements.
	 * @param int $count Number of elements.
	 * @param bool $surround If true, generate () at start and end of the result.
	 * @param string $glue Glue to use between the ? signs.
	 * @return string Return the string to use.
	 */
	public static function generatePlaceholder($count, $surround = false, $glue = ',') {
		$result = implode($glue, array_pad(array(), $count, '?'));
		return ($surround ? "($result)" : $result);
	}
}