<?php
namespace Squid\Utils\ColumnResolve;


use \Squid\Base\Utils\IColumnToPropertyResolve;


class PropertyResolve implements IColumnToPropertyResolve {
	
	/**
	 * @param mixed $object
	 * @param string $columnName
	 * @param mixed $value
	 */
	public function setValue($object, $columnName, $value) {
		$set = 'set' . ucfirst($columnName);
		$object->$set($value);
	}
	
	/**
	 * @param mixed $object
	 * @param string $columnName
	 * @return mixed
	 */
	public function getValue($object, $columnName) {
		$get = 'get' . ucfirst($columnName);
		return $object->$get();
	}
}