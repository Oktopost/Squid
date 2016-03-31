<?php
namespace Squid\Utils\Select;


use \Squid\Base\Utils\ISelectIntoObject;
use \Squid\Base\Utils\IColumnToPropertyResolve;
use \Squid\Base\Cmd\ICmdSelect;
use \Squid\Utils\ColumnResolve\PropertyResolve;


class ObjectSelect implements ISelectIntoObject {
	
	/**
	 * @param string|callable $class
	 * @param ICmdSelect $select
	 * @param IColumnToPropertyResolve $resolve
	 * @return mixed|false
	 */
	public function selectOne($class, ICmdSelect $select, IColumnToPropertyResolve $resolve = null) {
		$data = $select->queryRow(true);
		
		if (!$data) {
			return false;
		}
		
		return $this->loadObject($class, $data, $this->getResolve($resolve));
	}
	
	/**
	 * @param string|callable $class
	 * @param ICmdSelect $select
	 * @param IColumnToPropertyResolve $resolve
	 * @return array|false
	 */
	public function selectAll($class, ICmdSelect $select, IColumnToPropertyResolve $resolve = null) {
		$resolve	= $this->getResolve($resolve);
		$data		= $select->queryAll(true);
		$result		= array();
		
		if ($data === false) {
			return false;
		}
		
		foreach ($data as $row) {
			$result[] = $this->loadObject($class, $row, $resolve);
		}
		
		return $result;
	}
	
	
	/**
	 * @param string|callable $class
	 * @param array $data
	 * @param IColumnToPropertyResolve $resolve
	 * @return mixed
	 */
	private function loadObject($class, array $data, IColumnToPropertyResolve $resolve) {
		$object = $this->getObject($class);
		
		foreach ($data as $column => $value) {
			$resolve->setValue($object, $column, $value);
		}
		
		return $object;
	}
	
	/**
	 * @param IColumnToPropertyResolve|null $resolve
	 * @return IColumnToPropertyResolve
	 */
	private function getResolve(IColumnToPropertyResolve $resolve = null) {
		if (!$resolve) {
			return new PropertyResolve();
		}
		
		return $resolve;
	}
	
	/**
	 * @param string|callable $class
	 * @return mixed
	 * @throws \Exception
	 */
	private function getObject($class) {
		if (is_string($class)) {
			return new $class;
		} else if (is_callable($class)) {
			return $class();
		}
		
		throw new \Exception('Class must be a string or a callback!');
	}
}