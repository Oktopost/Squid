<?php
namespace Squid\MySql\Command;


interface IWithSet 
{
	/**
	 * @param string|array $field 
	 * @param mixed|array|bool $value
	 * @return static
	 */
	public function set($field, $value = false);
	
	/** 
	 * @param string|array
	 * @param string|array|bool $exp
	 * @param array|bool $bind
	 * @return static
	 */
	public function setExp($field, $exp = false, $bind = []);
	
	/**
	 * @param string $field
	 * @param string $caseField
	 * @param array $whenValuesThen
	 * @param string|bool $elseValue
	 * @return static
	 */
	public function setCase($field, $caseField, array $whenValuesThen, $elseValue = false);
	
	/**
	 * @param string $field
	 * @param string $caseExp
	 * @param array $whenValuesThenExp
	 * @param string|bool $elseExp
	 * @param string|bool $bindParams
	 * @return static
	 */
	public function setCaseExp($field, $caseExp, array $whenValuesThenExp, $elseExp = false, $bindParams = false);
}