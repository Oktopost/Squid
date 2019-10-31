<?php
namespace Squid\MySql\Command\Create;


/**
 * @see http://dev.mysql.com/doc/refman/5.7/en/data-types.html
 */
interface IColumnFactory
{
	/**
	 * Create new CHAR column
	 * @param int $length
	 * @return IColumn
	 */
	public function char($length);
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function varchar($length);
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function tinyint($length = 1);
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function smallint($length = 1);
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function mediumint($length = 1);
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function int($length = 11);
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function bigint($length = 1);

	/**
	 * @see https://dev.mysql.com/doc/refman/5.7/en/precision-math-decimal-characteristics.html
	 * @param int $precision
	 * @param int $scale
	 * @return IColumn
	 */
	public function decimal($precision, $scale);
	
	/**
	 * Equivalent to decimal($intDigits + $fractionDigits, $fractionDigits);
	 * @param int $intDigits
	 * @param int $fractionDigits
	 * @return IColumn
	 */
	public function createDecimal($intDigits, $fractionDigits);
	
	/**
	 * @return IColumn
	 */
	public function date();
	
	/**
	 * @return IColumn
	 */
	public function time();
	
	/**
	 * @return IColumn
	 */
	public function year();
	
	/**
	 * @return IColumn
	 */
	public function dateTime();
	
	/**
	 * @return IColumn
	 */
	public function timeStamp();
	
	/**
	 * @return IColumn
	 */
	public function text();
	
	/**
	 * @param array $values
	 * @return IColumn
	 */
	public function enum(array $values);
	
	/**
	 * @param array $values
	 * @return IColumn
	 */
	public function set(array $values);
	
	/**
	 * @return IColumn
	 */
	public function json();
	
	/**
	 * 
	 * @return IColumn
	 */
	public function bool();
}