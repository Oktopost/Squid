<?php
namespace Squid\MySql\Impl\Connectors\Utils\Select;


use Squid\MySql\IMySqlConnector;
use Squid\MySql\Command\ISelect;
use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Command\IMySqlCommandConstructor;
use Squid\MySql\Impl\Traits\CmdTraits\TWithLimit;
use Squid\MySql\Impl\Traits\CmdTraits\TWithWhere;
use Squid\MySql\Impl\Traits\CmdTraits\TWithColumn;
use Squid\MySql\Impl\Traits\CmdTraits\TWithExtendedWhere;


class SelectDecorator implements ISelect
{
	use TWithWhere;
	use TWithLimit;
	use TWithColumn;
	use TWithExtendedWhere;
	
	
	/** @var ICmdSelect */
	private $select;
	
	
	protected function addColumn($columns, $bind) { $this->select->columnsExp($columns, $bind); return $this; }
	protected function _orderBy(array $expressions) { $this->select->orderBy($expressions); return $this; }
	
	
	protected function getSelect(): ICmdSelect
	{
		return $this->select;
	}


	/**
	 * @param ISelect|IMySqlConnector $from
	 */
	public function __construct($from = null)
	{
		if ($from instanceof ISelect)
		{
			$this->setChild($from);
		}
		else if ($from)
		{
			$this->setConnector($from);
		}
	}
	
	
	/**
	 * @param ISelect $select
	 * @return static
	 */
	public function setChild(ISelect $select)
	{
		$this->select = $select;
		return $this;
	}
	
	/**
	 * @param IMySqlConnector $connector
	 * @return static|SelectDecorator
	 */
	public function setConnector(IMySqlConnector $connector)
	{
		$this->select = $connector->select();
		return $this;
	}
	
	
	public function distinct($distinct = true) { $this->select->distinct($distinct); return $this; }
	public function from($table, $alias = false) { $this->select->from($table, $alias); return $this; }
	public function join($table, $alias, $condition, $bind = false) { $this->select->join($table, $alias, $condition, $bind); return $this; }
	public function leftJoin($table, $alias, $condition, $bind = false, $outer = false) { $this->select->leftJoin($table, $alias, $condition, $bind, $outer); return $this; }
	public function rightJoin($table, $alias, $condition, $bind = false, $outer = false) { $this->select->rightJoin($table, $alias, $condition, $bind, $outer); return $this; }
	public function groupBy($column, $bind = false) { $this->select->groupBy($column, $bind); return $this; }
	public function having($exp, $bind = false) { $this->select->having($exp, $bind); return $this; }
	public function union(IMySqlCommandConstructor $select, $all = false) { $this->select->union($select, $all); return $this; }
	public function unionAll(IMySqlCommandConstructor $select) { $this->select->unionAll($select); return $this; }
	
	public function where($exp, $bind = false) { $this->select->where($exp, $bind); return $this; }
	public function limit($from, $count) { $this->select->limit($from, $count); return $this; }
	public function withRollup($withRollup = true) { $this->select->withRollup($withRollup); return $this; }
	public function forUpdate($forUpdate = true) { $this->select->forUpdate($forUpdate); return $this; }
	public function lockInShareMode($lockInShareMode = true) { $this->select->lockInShareMode($lockInShareMode); return $this; }
	
	
	public function __clone()
	{
		$this->select = clone $this->select;
	}
	
	public function __toString()
	{
		return (string)$this->select;
	}
}