<?php
namespace Squid\MySql\Utils;


interface IColumnToPropertyResolve {
	
	/**
	 * @param mixed $object
	 * @param string $columnName
	 * @param mixed $value
	 */
	public function setValue($object, $columnName, $value);
	
	/**
	 * @param mixed $object
	 * @param string $columnName
	 * @return mixed
	 */
	public function getValue($object, $columnName);
}