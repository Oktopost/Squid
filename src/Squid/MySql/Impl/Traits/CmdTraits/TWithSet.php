<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


/**
 * Implements calculation behavior for the IWithSet interface. Relies on the using class
 * to implement method _set($exp, $bind).
 * @method mixed _set(string $exp, $bind = false)
 * @see \Squid\MySql\Command\IWithSet
 */
trait TWithSet 
{
	/**
	 * @param array|bool $value
	 * @inheritdoc
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
	 * @param string|array|false $exp
	 * @inheritdoc
	 */
	public function setExp($field, $exp = false, $bind = false) 
	{
		if (!is_array($field)) return $this->_set("$field=$exp", $bind);
		
		if (isset($field[0])) return $this->setExpressionFields($field, $exp, $bind);
		
		return $this->setExpresionFieldsAssoc($field);
	}
	
	/**
	 * @see https://dev.mysql.com/doc/refman/5.7/en/case.html
	 * @inheritdoc
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
	 * @see https://dev.mysql.com/doc/refman/5.7/en/case.html
	 * @inheritdoc
	 */
	public function setCaseExp($field, $caseExp, array $whenValuesThenExp, $elseExp = false, $bindParams = false) 
	{
		$statment = '';
		
		foreach ($whenValuesThenExp as $when => $then) 
		{
			$statment .= "WHEN $when THEN $then ";
		}
		
		if ($elseExp) 
			$statment .= "ELSE $elseExp ";
		
		return $this->_set("$field=CASE $caseExp {$statment}END", $bindParams);
	}
	
	
	/**
	 * @inheritdoc
	 */
	private function setFields($fields, $values)
	{
		$fieldsCount = count($fields);
		
		for ($i = 0; $i < $fieldsCount; $i++) 
		{
			$this->setField($fields[$i], $values[$i]);
		}
		
		return $this;
	}
	
	/**
	 * @inheritdoc
	 */
	private function setFieldsAssoc($fields)
	{
		foreach ($fields as $field => $value)
		{
			$this->setField($field, $value);
		}
		
		return $this;
	}
	
	/**
	 * @inheritdoc
	 */
	private function setField($field, $value) 
	{
		if (is_null($value)) 
		{
			$exp = 'NULL';
			$value = false;
		}
		else 
		{
			$exp = '?';
		}
		
		return $this->_set("$field=$exp", $value);
	}
	
	/**
	 * @inheritdoc
	 */
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
	
	/**
	 * @inheritdoc
	 */
	private function setExpresionFieldsAssoc($fields)
	{
		foreach ($fields as $field => $exp) 
		{
			$this->_set("$field=$exp");
		}
		
		return $this;
	}
}