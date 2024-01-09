<?php
namespace Squid\MySql\Impl\Connectors\Utils\Select;


use Squid\MySql\Command\IWithWhere;
use Squid\MySql\IMySqlConnector;
use Squid\MySql\Command\ISelect;
use Squid\MySql\Command\IWithLimit;
use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Command\IMySqlCommandConstructor;
use Squid\MySql\Impl\Traits\CmdTraits\TWithLimit;
use Squid\MySql\Impl\Traits\CmdTraits\TWithColumn;
use Squid\MySql\Impl\Traits\CmdTraits\TWithExtendedWhere;
use Squid\MySql\Impl\Traits\CmdTraits\Decorators\TWithWhereDecorated;


class SelectDecorator implements ISelect
{
	use TWithLimit;
	use TWithColumn;
	use TWithExtendedWhere;
	use TWithWhereDecorated;
	
	
	/** @var ICmdSelect */
	private $select;
	
	
	protected function addColumn($columns, $bind) { $this->select->columnsExp($columns, $bind); return $this; }
	protected function _orderBy(array $expressions) { $this->select->orderBy($expressions); return $this; }
	
	
	protected function getSelect(): ICmdSelect
	{
		return $this->select;
	}

	protected function getChild(): IWithWhere
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
	
	
	public function distinct(bool $distinct = true) { $this->select->distinct($distinct); return $this; }
	public function from($table, ?string $alias = null, bool $escape = true) { $this->select->from($table, $alias, $escape); return $this; }
	public function join($table, string $alias, string $condition, $bind = []) { $this->select->join($table, $alias, $condition, $bind); return $this; }
	public function leftJoin($table, string $alias, string $condition, $bind = [], bool $outer = false) { $this->select->leftJoin($table, $alias, $condition, $bind, $outer); return $this; }
	public function rightJoin($table, string $alias, string $condition, $bind = [], bool $outer = false) { $this->select->rightJoin($table, $alias, $condition, $bind, $outer); return $this; }
	public function groupBy($column, $bind = []) { $this->select->groupBy($column, $bind); return $this; }
	public function having(string $exp, $bind = []) { $this->select->having($exp, $bind); return $this; }
	public function union(IMySqlCommandConstructor $select, bool $all = false) { $this->select->union($select, $all); return $this; }
	public function unionAll(IMySqlCommandConstructor $select) { $this->select->unionAll($select); return $this; }
	
	public function where(string $exp, $bind = []) { $this->select->where($exp, $bind); return $this; }
	public function limit($from, $count): IWithLimit { $this->select->limit($from, $count); return $this; }
	public function withRollup(bool $withRollup = true) { $this->select->withRollup($withRollup); return $this; }
	public function forUpdate(bool $forUpdate = true) { $this->select->forUpdate($forUpdate); return $this; }
	public function lockInShareMode(bool $lockInShareMode = true) { $this->select->lockInShareMode($lockInShareMode); return $this; }


	public function __clone()
	{
		$this->select = clone $this->select;
	}
	
	public function __toString()
	{
		return (string)$this->select;
	}
	
	public function whereLike(string $exp, $value, ?string $escapeChar = null)
	{
		// TODO: Implement whereLike() method.
	}
	
	public function whereNotLike(string $exp, $value, ?string $escapeChar = null)
	{
		// TODO: Implement whereNotLike() method.
	}
	
	public function whereContains(string $exp, $value, bool $negate = false)
	{
		// TODO: Implement whereContains() method.
	}
	
	public function whereStartsWith(string $exp, $value, bool $negate = false)
	{
		// TODO: Implement whereStartsWith() method.
	}
	
	public function whereEndsWith(string $exp, $value, bool $negate = false)
	{
		// TODO: Implement whereEndsWith() method.
	}
}