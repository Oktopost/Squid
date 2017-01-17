<?php
namespace Squid\MySql\Impl\Command\Create;


use Squid\MySql\Command\Create\IColumn;
use Squid\MySql\Command\Create\IColumnFactory;
use Squid\Exceptions\SquidException;


class ColumnFactory implements IColumnFactory
{
	/**
	 * @param string $type
	 * @param int|string|null $length
	 * @return IColumn
	 */
	private function create($type, $length = null)
	{
		return (new Column())->type($type, $length);
	}
	
	
	public function char($length) { return $this->create('CHAR', $length); }
	public function varchar($length) { return $this->create('VARCHAR', $length); }
	
	public function tinyint($length = 4) { return $this->create('TINYINT', $length); }
	public function smallint($length = 6) { return $this->create('SMALLINT', $length); }
	public function mediumint($length = 9) { return $this->create('MEDIUMINT', $length); }
	public function int($length = 11) { return $this->create('INT', $length); }
	public function bigint($length = 20) { return $this->create('BIGINT', $length); }
	
	public function date() { return $this->create('DATE'); }
	public function time() { return $this->create('TIME'); }
	public function year() { return $this->create('YEAR'); }
	public function dateTime() { return $this->create('DATETIME'); }
	public function timeStamp() { return $this->create('TIMESTAMP'); }
	public function text() { return $this->create('TEXT'); }
	public function enum(array $values) { return $this->create("ENUM('" . implode("','", $values) . "')"); }
	public function set(array $values) { return $this->create("SET('" . implode("','", $values) . "')"); }
	public function json() { return $this->create('JSON'); }
	public function bool() { return $this->tinyint(1); }
	
	
	public function decimal($precision, $scale)
	{
		if ($scale > $precision) 
			throw new SquidException('Scale must not be greater then precision!');
		
		return $this->create('DECIMAL', "$precision,$scale");
	}
	
	public function createDecimal($intDigits, $fractionDigits)
	{
		return $this->decimal($intDigits + $fractionDigits, $fractionDigits);
	}
}