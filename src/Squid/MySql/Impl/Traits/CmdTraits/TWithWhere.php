<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\MySql\Command\ICmdSelect;


/**
 * Implements calculation behavior for the IWithWhere interface. Only method that is not implemented, 
 * is where. Where must be implemented by the using class.
 * @method mixed where(string $exp, $bind = false)
 */
trait TWithWhere {
	
	/**
	 * Append section comapring field named 'Id' to given value.
	 * @param mixed $value Value to compare to Id.
	 * @return mixed Always returns self.
	 */
	public function byId($value) {
		return $this->byField('Id', $value);
	}
	
	/**
	 * Append section comparing field $field to value $value.
	 * @param string $field Name of the field to compare.
	 * @param string $value Value to compare to.
	 * @return mixed Always returns self.
	 */
	public function byField($field, $value) {
		if (is_null($value)) {
			return $this->where("ISNULL($field)");
		}
		
		return $this->where("$field=?", $value);
	}
	
	/**
	 * Compare number of fields to given values.
	 * @param array $fields Either numeric array of field names or associative array
	 * where key is fields name, and value is the value to compare this field to.
	 * @param array $values Array of values to compare to.
	 * @return mixed Always returns self.
	 */
	public function byFields($fields, $values = null) {
		if (isset($fields[0])) {
			return $this->byFieldsNum($fields, $values);
		}
		
		return $this->byFieldsAssoc($fields);
	}
	
	/**
	 * Search for an expression or field in given set or subquery.
	 * @param string $field Field (cna also be expression) to compare to the set 
	 * of values (or sub query).
	 * @param array|ICmdSelect $values Array of values to search in, or sub query.
	 * @param bool $negate If true use NOT IN statment.
	 * @return mixed Always returns self.
	 */
	public function whereIn($field, $values, $negate = false) {
		if (!$values) {
			throw new \Exception('Empty values set passed to whereIn!');
		}
		
		if ($values instanceof ICmdSelect) {
			$in = $values->assemble();
			$values = $values->bind();
		} else {
			$in = implode(',', array_pad(array(), count($values), '?'));
		}
		
		$statment = ($negate ? 'NOT IN' : 'IN');
		
		return $this->where("$field $statment ($in)", $values);
	}
	
	/**
	 * Same as whereIn but always with NOT IN expression.
	 * @param string $field Field (cna also be expression) to compare to the set 
	 * of values (or sub query).
	 * @param array|ICmdSelect $values Array of values to search in, or sub query.
	 * @return mixed Always returns self.
	 */
	public function whereNotIn($field, $values) {
		return $this->whereIn($field, $values, true);
	}
	
	/**
	 * Append is exists or not exists statment.
	 * @param ICmdSelect $select
	 * @param bool $negate If true use NOT EXISTS statment.
	 * @return mixed Always returns self.
	 */
	public function whereExists(ICmdSelect $select, $negate = false) {
		$in = $select->assemble();
		$statment = ($negate ? 'NOT EXISTS' : 'EXISTS');
		
		return $this->where("$statment ($in)", $select->bind());
	}
	
	/**
	 * Same as whereExists but always with NOT EXISTS expression.
	 * @param ICmdSelect $select
	 * @param bool $negate If true use NOT EXISTS statment.
	 * @return mixed Always returns self.
	 */
	public function whereNotExists(ICmdSelect $select) {
		return $this->whereExists($select, true);
	}
	
	
	/**
	 * @param array $fields Numeric array of field names.
	 * @param array $values
	 * @return mixed Always returns self.
	 */
	private function byFieldsNum($fields, $values) {
		$fieldsCount = count($fields);
		
		for ($i = 0; $i < $fieldsCount; $i++) {
			$self = $this->byField($fields[$i], $values[$i]);
		}
		
		return $self;
	}
	
	/**
	 * @param array $fields Associative array, where key is fields name, 
	 * and value is the value to compare this field to.
	 * @return mixed Always returns self.
	 */
	private function byFieldsAssoc($fields) {
		foreach ($fields as $field => $value) {
			$self = $this->byField($field, $value);
		}
		
		return $self;
	}
}