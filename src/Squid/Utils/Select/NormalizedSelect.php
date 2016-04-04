<?php
namespace Squid\Utils\Select;


use Squid\MySql\Utils\ISelectIntoObject;
use Squid\MySql\Utils\IColumnToPropertyResolve;
use Squid\MySql\Command\ICmdSelect;
use Squid\Utils\ColumnResolve\PropertyResolve;


class NormalizedSelect implements ISelectIntoObject {
	
	/**
	 * @param string|callable $class
	 * @param ICmdSelect $select
	 * @param IColumnToPropertyResolve $resolve
	 * @return array|false
	 */
	public function selectOne($class, ICmdSelect $select, IColumnToPropertyResolve $resolve = null) {
		$data		= $select->queryAll(false);
		$object		= (is_string($class) ? new $class : $class());
		$resolve	= $this->getResolve($resolve);
		
		if ($data === false) {
			return false;
		}
		
		foreach ($data as $row) {
			$resolve->setValue($object, $row[0], $row[1]);
		}
		
		return $object;
	}
	
	/**
	 * @param string|callable $class
	 * @param ICmdSelect $select
	 * @param IColumnToPropertyResolve $resolve
	 * @return mixed|false
	 */
	public function selectAll($class, ICmdSelect $select, IColumnToPropertyResolve $resolve = null) {
		throw new \Exception('Operation not supported!');
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
}