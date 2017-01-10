<?php
namespace Squid\MySql\Impl\Command\Create;


use Squid\MySql\Command\Create\IColumn;
use Squid\MySql\Command\Create\IColumnFactory;


class ColumnFactory implements IColumnFactory
{
	/**
	 * @param string $type
	 * @param int|null $length
	 * @return IColumn
	 */
	private function create($type, $length = null)
	{
		return (new Column())->type($type, $length);
	}
	
	
	/**
	 * Create new CHAR column
	 * @param int $length
	 * @return IColumn
	 */
	public function char($length) { return $this->create('CHAR', $length); }
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function varchar($length) { return $this->create('VARCHAR', $length); }
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function tinyint($length = 1) { return $this->create('TINYINT', $length); }
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function smallint($length = 1) { return $this->create('SMALLINT', $length); }
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function mediumint($length = 1) { return $this->create('MEDIUMINT', $length); }
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function int($length = 11) { return $this->create('INT', $length); }
	
	/**
	 * @param int $length
	 * @return IColumn
	 */
	public function bigint($length = 1) { return $this->create('BIGINT', $length); }
	
	/**
	 * @return IColumn
	 */
	public function date() { return $this->create('DATE'); } 
	
	/**
	 * @return IColumn
	 */
	public function time() { return $this->create('TIME'); } 
	
	/**
	 * @return IColumn
	 */
	public function year() { return $this->create('YEAR'); } 
	
	/**
	 * @return IColumn
	 */
	public function dateTime() { return $this->create('DATETIME'); } 
	
	/**
	 * @return IColumn
	 */
	public function timeStamp() { return $this->create('TIMESTAMP'); } 
	
	/**
	 * @return IColumn
	 */
	public function text() { return $this->create('TEXT'); } 
	
	/**
	 * @param array $values
	 * @return IColumn
	 */
	public function enum(array $values) { return $this->create("ENUM('" . implode("','", $values) . "')"); }
	
	/**
	 * @param array $values
	 * @return IColumn
	 */
	public function set(array $values) { return $this->create("SET('" . implode("','", $values) . "')"); }
	
	/**
	 * @return IColumn
	 */
	public function json() { return $this->create('JSON'); }
	
	/**
	 * @return IColumn
	 */
	public function bool() { return $this->tinyint(1); }
}