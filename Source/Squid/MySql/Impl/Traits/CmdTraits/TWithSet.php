<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


/**
 * Implements calculation behavior for the IWithSet interface. Relies on the using class
 * to implement method _set($exp, $bind).
 * @method static _set(string $exp, $bind = [])
 * @see \Squid\MySql\Command\IWithSet
 */
trait TWithSet 
{
	private function setFields($fields, $values)
	{
		$fieldsCount = count($fields);
		
		for ($i = 0; $i < $fieldsCount; $i++)
		{
			$this->setField($fields[$i], $values[$i]);
		}
		
		return $this;
	}
	
	private function setFieldsAssoc($fields)
	{
		foreach ($fields as $field => $value)
		{
			$this->setField($field, $value);
		}
		
		return $this;
	}
	
	private function setField($field, $value)
	{
		if (is_null($value))
		{
			$exp = 'NULL';
			$value = [];
		}
		else
		{
			$exp = '?';
			$value = [$value];
		}
		
		return $this->_set("$field=$exp", $value);
	}
	
	private function setExpressionFields($fields, $expressions, $bind)
	{
		$fieldsCount = count($fields);
		
		for ($i = 0; $i < $fieldsCount; $i++)
		{
			$currBind = ($bind ? $bind[$i] : false);
			$this->_set("$fields[$i]=$expressions[$i]", $currBind);
		}
		
		return $this;
	}
	
	private function setExpressionFieldsAssoc($fields)
	{
		foreach ($fields as $field => $exp)
		{
			$this->_set("$field=$exp");
		}
		
		return $this;
	}
	
	
	/**
	 * @param string|array $field
	 * @param mixed|array|bool $value
	 * @return static
	 */
	public function set($field, $value = false) 
	{
		if (!is_array($field)) return $this->setField($field, $value);
		
		// Numeric array.
		if (isset($field[0])) return $this->setFields($field, $value);
		
		// Assoc array.
		return $this->setFieldsAssoc($field);
	}
	
	/** 
	 * @param string|array
	 * @param string|array|bool $exp
	 * @param array|bool $bind
	 * @return static
	 */
	public function setExp($field, $exp = false, $bind = []) 
	{
		if (!is_array($field)) return $this->_set("$field=$exp", $bind);
		
		if (isset($field[0])) return $this->setExpressionFields($field, $exp, $bind);
		
		return $this->setExpressionFieldsAssoc($field);
	}
	
	/**
	 * @param string $field
	 * @param string $caseField
	 * @param array $whenValuesThen
	 * @param string|bool $elseValue
	 * @return static
	 */
	public function setCase($field, $caseField, array $whenValuesThen, $elseValue = false) 
	{
		$statement = implode('', array_fill(0, count($whenValuesThen), "WHEN ? THEN ? "));
		$bindParams = array();
		
		foreach ($whenValuesThen as $when => $then)
		{
			$bindParams[] = $when;
			$bindParams[] = $then;
		}
		
		if ($elseValue)
		{
			$statement .= 'ELSE ? ';
			$bindParams[] = $elseValue;
		}
		
		return $this->_set("$field=CASE $caseField {$statement}END", $bindParams);
	}
	
	/**
	 * @param string $field
	 * @param string $caseExp
	 * @param array $whenValuesThenExp
	 * @param string|bool $elseExp
	 * @param string|bool $bindParams
	 * @return static
	 */
	public function setCaseExp($field, $caseExp, array $whenValuesThenExp, $elseExp = false, $bindParams = false) 
	{
		$statement = '';
		
		foreach ($whenValuesThenExp as $when => $then) 
		{
			$statement .= "WHEN $when THEN $then ";
		}
		
		if ($elseExp) 
			$statement .= "ELSE $elseExp ";
		
		return $this->_set("$field=CASE $caseExp {$statement}END", $bindParams);
	}
}