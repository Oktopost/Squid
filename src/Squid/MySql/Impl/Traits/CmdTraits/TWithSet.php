<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


/**
 * Implements calculation behavior for the IWithSet interface. Relies on the using class
 * to implement method _set($exp, $bind).
 * @method mixed _set(string $exp, $bind = false)
 */
trait TWithSet {
	
	/**
	 * Append a set of SET subqueries, or a ingle query.
	 * @param string|array $field If single string, $value must be the new value 
	 * that will be assigned to this field. If array, than can be one of:
	 * a. Numeric array of fields, in this case $value must be array of values for each
	 * field.
	 * b. Associative array, in wich case key is the field name, and value
	 * is the value of this field.
	 * @param mixed|array|null $value Single value to set to a single field, or array of 
	 * values for each field in numeric array $field.
	 * @return mixed Always returns self.
	 */
	public function set($field, $value = false) {
		if (!is_array($field)) {
			return $this->setField($field, $value);
		}
		
		// Numeric array.
		if (isset($field[0])) {
			return $this->setFields($field, $value);
		}
		
		// Assoc array.
		return $this->setFieldsAssoc($field);
	}
	
	/**
	 * Append set sub query with an expression. 
	 * @param string|array $field Name of the field. Or array of fields, or assoc array where
	 * key is field name and value is the expression.
	 * @param string|array|false $exp Single expression if $field is single value. Array 
	 * of expressions if $field is array, or false if $field is assoc array.
	 * @param array|null $bind If null, ignored, otherwise array of bind parameters, or 
	 * numeric array of arrays with parameters if $field is a numeric array (sizes must match).
	 * @return mixed Always returns self.
	 */
	public function setExp($field, $exp = false, $bind = false) {
		if (!is_array($field)) {
			return $this->_set("$field=$exp", $bind);
		}
		
		if (isset($field[0])) {
			return $this->setExpresionFields($field, $exp, $bind);
		}
		
		return $this->setExpresionFieldsAssoc($field);
	}
	
	/**
	 * @see https://dev.mysql.com/doc/refman/5.7/en/case.html
	 * @param string $field
	 * @param string $caseField
	 * @param array $whenValuesThen
	 * @param string $elseValue
	 */
	public function setCase($field, $caseField, array $whenValuesThen, $elseValue = false) {
		$statment = implode('', array_fill(0, count($whenValuesThen), "WHEN ? THEN ? "));
		$bindParams = array();
		
		foreach ($whenValuesThen as $when => $then) {
			$bindParams[] = $when;
			$bindParams[] = $then;
		}
		
		if ($elseValue) {
			$statment .= 'ELSE ? ';
			$bindParams[] = $elseValue;
		}
		
		return $this->_set("$field=CASE $caseField {$statment}END", $bindParams);
	}
	
	/**
	 * @see https://dev.mysql.com/doc/refman/5.7/en/case.html
	 * @param string $field
	 * @param string $caseExp
	 * @param array $whenValuesThenExp
	 * @param string $elseExp
	 * @param string $bindParams
	 */
	public function setCaseExp($field, $caseExp, array $whenValuesThenExp, $elseExp = false, $bindParams = false) {
		$statment = '';
		
		foreach ($whenValuesThenExp as $when => $then) {
			$statment .= "WHEN $when THEN $then ";
		}
		
		if ($elseExp) {
			$statment .= "ELSE $elseExp ";
		}
		
		return $this->_set("$field=CASE $caseExp {$statment}END", $bindParams);
	}
	
	
	/**
	 * Called for array of fields and array of values.
	 * @param array $fields Fields to set.
	 * @param array $values Fields' values.
	 * @return mixed Always returns self.
	 */
	private function setFields($fields, $values) {
		$fieldsCount = count($fields);
		
		for ($i = 0; $i < $fieldsCount; $i++) {
			$this->setField($fields[$i], $values[$i]);
		}
		
		return $this;
	}
	
	/**
	 * Called for associative array of fields.
	 * @param array $fields Associative array of fileds and there value.
	 * @return mixed Always returns self.
	 */
	private function setFieldsAssoc($fields) {
		foreach ($fields as $field => $value) {
			$this->setField($field, $value);
		}
		
		return $this;
	}
	
	/**
	 * Called for a single field set.
	 * @param string $field Field to set.
	 * @param mixed $value Field value.
	 * @return mixed Always returns self.
	 */
	private function setField($field, $value) {
		if (is_null($value)) {
			$exp = 'NULL';
			$value = false;
		} else {
			$exp = '?';
		}
		
		return $this->_set("$field=$exp", $value);
	}
	
	/**
	 * Called for associative array of fields.
	 * @param array $fields Numeric array of fields to set.
	 * @param array $exps Numeric array of expressions.
	 * @param array|bool $bind Array of array of bind values for each field, or false to ignore.
	 * @return mixed Always returns self.
	 */
	private function setExpresionFields($fields, $exps, $bind) {
		$fieldsCount = count($fields);
		
		for ($i = 0; $i < $fieldsCount; $i++) {
			$currBind = ($bind ? $bind[$i] : false);
			$this->_set("$fields[$i]=$exps[$i]", $currBind);
		}
		
		return $this;
	}
	
	/**
	 * Called for associative array of fields with expressions.
	 * @param array $fields Associative array of fileds and there expressions.
	 * @param array|bool $bind Array of array of bind values for each field, or false to ignore.
	 * @return mixed Always returns self.
	 */
	private function setExpresionFieldsAssoc($fields) {
		foreach ($fields as $field => $exp) {
			$this->_set("$field=$exp");
		}
		
		return $this;
	}
}