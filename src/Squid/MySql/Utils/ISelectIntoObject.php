<?php
namespace Squid\MySql\Utils;


use Squid\MySql\Command\ICmdSelect;


interface ISelectIntoObject {
	
	/**
	 * @param string|callable $class
	 * @param ICmdSelect $select
	 * @param IColumnToPropertyResolve $resolve
	 * @return mixed|false
	 */
	public function selectOne($class, ICmdSelect $select, IColumnToPropertyResolve $resolve = null);
	
	/**
	 * @param string|callable $class
	 * @param ICmdSelect $select
	 * @param IColumnToPropertyResolve $resolve
	 * @return array|false
	 */
	public function selectAll($class, ICmdSelect $select, IColumnToPropertyResolve $resolve = null);
}