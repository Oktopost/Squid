<?php
namespace Squid\Utils\ColumnResolve;


use \Squid\Base\Utils\IColumnToPropertyResolve;


class SameNameResolve implements IColumnToPropertyResolve {
	
	/**
	 * @param mixed $object
	 * @param string $columnName
	 * @param mixed $value
	 */
	public function setValue($object, $columnName, $value) {
		$object->$columnName($value);
	}
	
	/**
	 * @param mixed $object
	 * @param string $columnName
	 * @return mixed
	 */
	public function getValue($object, $columnName) {
		return $object->$columnName();
	}
}