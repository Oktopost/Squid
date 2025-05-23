<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\ICmdInsert;
use Squid\MySql\Command\ICmdSelect;
use Squid\Exceptions\SquidException;


class CmdInsert extends PartsCommand implements ICmdInsert
{
	use \Squid\MySql\Impl\Traits\CmdTraits\TDml;
	
	
	const PART_IGNORE	= 0;
	const PART_INTO		= 1;
	const PART_AS		= 2;
	const PART_VALUES	= 3;
	
	
	/**
	 * @var array Collection of default values.
	 */
	private static $DEFAULT = [
		CmdInsert::PART_IGNORE	=> false,
		CmdInsert::PART_INTO	=> false,
		CmdInsert::PART_AS		=> false,
		CmdInsert::PART_VALUES	=> false
	];
	
	
	private $placeholder	= '';
	
	/**
	 * @var array|bool False if not set; otherwise array of fields to insert into.
	 */
	private $fields = false;
	
	/** @var array|bool */
	private $default = false;
	
	
	
	/**
	 * Any value not present in the values set, and with a default value,
	 * should be appended to the given value set.
	 * @param array $values Array of values for a single row to modify.
	 */
	private function fixDefaultValues(&$values)
	{
		if (!$this->default) return;
		
		$values = array_merge($this->default, $values);
	}
	
	/**
	 * Append associative array of values.
	 * @param array $values Key is field name.
	 * @return static
	 */
	private function appendByField($values)
	{
		$fixed = array();
		
		foreach ($this->fields as $field)
		{
			$fixed[] = $values[$field];
		}
		
		return $this->appendByPosition($fixed);
	}
	
	/**
	 * Append numeric array of values.
	 * @param array $values Numeric array of values where position of each value matches the
	 * position of it's filed in $fields private data member.
	 * @return static
	 */
	private function appendByPosition($values)
	{
		$this->setPart(CmdInsert::PART_AS, false);
		
		if (!$this->placeholder)
			$this->placeholder = Assembly::placeholder(count($values), true);
		
		return $this->appendPart(
			CmdInsert::PART_VALUES,
			$this->placeholder,
			$values
		);
	}
	
	
	/**
	 * Get the parts this query can have.
	 * @return array Array containing only the part as keys and values set to false.
	 */
	protected function getDefaultParts() 
	{
		return CmdInsert::$DEFAULT;
	}
	
	/**
	 * Combined all the parts into one sql.
	 * @return string Created query.
	 */
	protected function generate()
	{
		if (!$this->getPart(CmdInsert::PART_INTO))
			throw new SquidException('Target table must be defined for Insert command. ' .
				'into() was not called or called with empty value');
		
		$command =  'INSERT ' . 
			($this->getPart(CmdInsert::PART_IGNORE) ? 'IGNORE ' : '') . 
			'INTO `' . $this->getPart(CmdInsert::PART_INTO) . '` ';
		
		if ($this->fields)
		{
			$command .= '(`' . implode('`,`', $this->fields) . '`) ';
		}
		
		$as = $this->getPart(CmdInsert::PART_AS);
		
		if ($as)
		{
			return $command . $as;
		}
		
		return $command . Assembly::append('VALUES', $this->getPart(CmdInsert::PART_VALUES), ', ');
	}
	
	
	/**
	 * Set the status of the ignore flag.
	 * @param bool $ignore If true, use ignore flag, otherwise don't.
	 * @return static
	 */
	public function ignore(bool $ignore = true)
	{
		return $this->setPart(CmdInsert::PART_IGNORE, $ignore);
	}
	
	/**
	 * Set the table to select into.
	 * @param string $table Table name.
	 * @param array|null $fields Set of fields to insert data into. This can be ignored
	 * if set later using values with assoc array.
	 * @return static
	 */
	public function into(string $table, array $fields = null)
	{
		$this->setPart(CmdInsert::PART_INTO, $table);
		
		if (!is_null($fields))
		{
			$this->placeholder = false;
			$this->fields = $fields;
		}
		
		return $this;
	}
	
	/**
	 * Set the default values to use.
	 * @param array $default Assoc array of values where key is column name and value is
	 * the default value to use.
	 * @return static
	 */
	public function defaultValues(array $default)
	{
		$this->default = $default;
		return $this;
	}
	
	/**
	 * Append a set of values to insert.
	 * @param array $values If numeric array, values must match fields that were set earlier.
	 * If assoc array, key must be the field name. If defaultValues was called, any missing field value, 
	 * (if fields where set earlier) will be checked to have a default value.
	 * @return static
	 */
	public function values(array $values) 
	{
		if (isset($values[0]))
			return $this->appendByPosition($values);
		
		$this->fixDefaultValues($values);
		
		if (!$this->fields) 
		{
			$this->placeholder = false;
			$this->fields = array_keys($values);
			return $this->appendByPosition(array_values($values));
		}
		
		return $this->appendByField($values);
	}
	
	/**
	 * Append a number of rows at once.
	 * @param array $valuesSet Numeric array were each value is array of values to insert into the table.
	 * For each row in the set, values function must be called, so all rules applying on the
	 * $values parameter in values function, must apply on each value in the $valuesSet array.
	 * @return static
	 */
	public function valuesBulk(array $valuesSet)
	{
		foreach ($valuesSet as $row) 
		{
			$this->values($row);
		}
		
		return $this;
	}
	
	/**
	 * Insert data using given expression. That can be sued if some of the values are expressions.
	 * Expressions must be Sql safe.
	 * @param string $expression Expression to insert. Expression must start from ( and end with ). 
	 * Expression can contain a number of rows to insert. It will not be validated in no way so number 
	 * of fields must match number of table fields.
	 * @param mixed|array $bind Single bind param or array of bind params.
	 * @return static
	 */
	public function valuesExp(string $expression, array $bind = [])
	{
		return $this->appendPart(
			CmdInsert::PART_VALUES, 
			$expression,
			$bind
		);
	}
	
	/**
	 * Use select command to insert into the table. 
	 * Note that in this case no values can be bind to the table.
	 * @param ICmdSelect $select Select sub query used to retrieve the insert values.
	 * @return static
	 */
	public function asSelect(ICmdSelect $select)
	{
		$this->setPart(CmdInsert::PART_VALUES, false);
		return $this->setPart(CmdInsert::PART_AS, $select->assemble(), $select->bind());
	}
	
	/**
	 * Get the fields set for this insert.
	 * @return array Array of fields.
	 */
	public function getFields()
	{
		return $this->fields;
	}
}