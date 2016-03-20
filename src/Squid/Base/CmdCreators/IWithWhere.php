<?php
namespace Squid\Base\CmdCreators;


use \Squid\Base\CmdCreators\ICmdSelect;


/**
 * Set of functions used for any creator that have a where caluse.
 */
interface IWithWhere {
	
	/**
	 * Append section comapring field named 'Id' to given value.
	 * @param mixed $value Value to compare to Id.
	 * @return static
	 */
	public function byId($value);
	
	/**
	 * Append section comparing field $field to value $value.
	 * @param string $field Name of the field to comapre.
	 * @param string $value Value to comapre to.
	 * @return static
	 */
	public function byField($field, $value);
	
	/**
	 * Compare number of fields to given values.
	 * @param array $fields Either numeric array of field names or associative array
	 * where key is fields name, and value is the value to comapre this field to.
	 * @param array $values Array of values to compare to.
	 * @return static
	 */
	public function byFields($fields, $values = null);
	
	/**
	 * Add additional where caluse.
	 * @param string $exp Expression to append.
	 * @param mixed|array|null $bind Single bind value, array of values or false if no 
	 * bind values are needed for this expression.
	 * @return static
	 */
	public function where($exp, $bind = false);
	
	/**
	 * Search for an expression or field in given set or subquery.
	 * @param string $field Field (cna also be expression) to compare to the set 
	 * of values (or sub query).
	 * @param array|ICmdSelect $values Array of values to search in, or sub query.
	 * @param bool $negate If true use NOT IN statment.
	 * @return static
	 */
	public function whereIn($field, $values, $negate = false);
	
	/**
	 * Same as whereIn but always with NOT IN expression.
	 * @param string $field Field (cna also be expression) to compare to the set 
	 * of values (or sub query).
	 * @param array|ICmdSelect $values Array of values to search in, or sub query.
	 * @return static
	 */
	public function whereNotIn($field, $values);
	
	/**
	 * Append is exists or not exists statment.
	 * @param ICmdSelect $select Select to insert into the exist sub query.
	 * @param bool $negate If true use NOT EXISTS statment.
	 * @return static
	 */
	public function whereExists(ICmdSelect $select, $negate = false);
	
	/**
	 * Same as whereExists but always with NOT EXISTS expression.
	 * @param ICmdSelect $select Select to insert into the exist sub query.
	 * @return static
	 */
	public function whereNotExists(ICmdSelect $select);
}