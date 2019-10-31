<?php
namespace Squid\MySql\Command;


interface IWithWhere 
{
	/**
	 * @param array|string $value
	 * @return static
	 */
	public function byId($value);
	
	/**
	 * @param string $field
	 * @param array|string $value
	 * @return static
	 */
	public function byField($field, $value);
	
	/**
	 * Compare number of fields to given values.
	 * @param array $fields Either numeric array of field names or associative array
	 * where key is fields name, and value is the value to compare this field to.
	 * @param array $values Array of values to compare to.
	 * @return static
	 */
	public function byFields($fields, $values = null);
	
	/**
	 * Add additional where clause.
	 * @param string $exp Expression to append.
	 * @param mixed|array|null $bind Single bind value, array of values or false if no 
	 * bind values are needed for this expression.
	 * @return static
	 */
	public function where($exp, $bind = []);
	
	/**
	 * Search for an expression or field in given set or sub query.
	 * @param string|array $field Field (can also be expression) to compare to the set 
	 * of values (or sub query) or array of fields to compare with arrays of values (or sub query).
	 * @param array|ICmdSelect $values Array of values to search in, or sub query.
	 * @param bool $negate If true use NOT IN statement.
	 * @return static
	 */
	public function whereIn($field, $values, $negate = false);
	
	/**
	 * Same as whereIn but always with NOT IN expression.
	*  @param string|array $field Field (can also be expression) to compare to the set 
	 * of values (or sub query) or array of fields to compare with arrays of values (or sub query).
	 * @param array|ICmdSelect $values Array of values to search in, or sub query.
	 * @return static
	 */
	public function whereNotIn($field, $values);
	
	/**
	 * Append is exists or not exists statement.
	 * @param ICmdSelect $select Select to insert into the exist sub query.
	 * @param bool $negate If true use NOT EXISTS statement.
	 * @return static
	 */
	public function whereExists(ICmdSelect $select, $negate = false);
	
	/**
	 * @param ICmdSelect $select
	 * @return static
	 */
	public function whereNotExists(ICmdSelect $select);
}