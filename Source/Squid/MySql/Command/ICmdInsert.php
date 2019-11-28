<?php
namespace Squid\MySql\Command;


interface ICmdInsert extends IDml, IMySqlCommandConstructor 
{	
	/**
	 * Set the status of the ignore flag.
	 * @param bool $ignore If true, use ignore flag, otherwise don't.
	 * @return static
	 */
	public function ignore(bool $ignore = true);
	
	/**
	 * Set the table to select into.
	 * @param string $table Table name.
	 * @param array|null $fields Set of fields to insert data into. This can be ignored 
	 * if set later using values with assoc array.
	 * @return static
	 */
	public function into(string $table, array $fields = null);
	
	/**
	 * Set the default values to use.
	 * @param array $default Assoc array of values where key is column name and value is
	 * the default value to use.
	 * @return static
	 */
	public function defaultValues(array $default);
	
	/**
	 * Append a set of values to insert.
	 * @param array $values If numeric array, values must match fields that were set in into(...) method.
	 * If assoc array, key must be the field name. If defaultValues was called, any missing field value,
	 * will be set to the default. See defaultValues(...) method.
	 * @return static
	 */
	public function values(array $values);
	
	/**
	 * Append a number of rows at once.
	 * @param array $valuesSet Numeric array were each value is array of values to insert into the table.
	 * For each row in the set, values function must be called, so all rules applied on the
	 * $values parameter in values function, must apply on each value in the $valuesSet array.
	 * @return static
	 */
	public function valuesBulk(array $valuesSet);
	
	/**
	 * Insert data using row sql string. Note that expressions must be Sql safe.
	 * @param string $expression Expression to insert. Note that "(" and ")" are not appended.
	 * Expression can contain a number of rows to insert. It will not be validated in any way.
	 * @param array|mixed $bind
	 * @return static
	 */
	public function valuesExp(string $expression, array $bind = []);
	
	/**
	 * Use select command to insert into the table. 
	 * Note that in this case no values can be bind to the table.
	 * @param ICmdSelect $select Select sub query used to retrieve the insert values.
	 * @return static
	 */
	public function asSelect(ICmdSelect $select);
}