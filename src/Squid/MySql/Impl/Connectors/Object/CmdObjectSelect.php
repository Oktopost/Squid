<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\IMySqlConnector;
use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Connectors\IConnector;
use Squid\MySql\Connectors\Object\Selector\IQuerySelector;
use Squid\MySql\Connectors\Object\Selector\ICmdObjectSelect;
use Squid\MySql\Impl\Traits\CmdTraits\TWithColumn;
use Squid\MySql\Impl\Traits\CmdTraits\TWithLimit;
use Squid\MySql\Impl\Traits\CmdTraits\TWithWhere;
use Squid\MySql\Impl\Connectors\Connector;


class CmdObjectSelect implements ICmdObjectSelect
{
	use TWithWhere;
	use TWithLimit;
	use TWithColumn;
	
	
	/** @var ICmdSelect */
	private $select;
	
	/** @var IQuerySelector */
	private $selector;
	
	
	private function addColumn($columns, $bind) { $this->select->columnsExp($columns, $bind); return $this; }
	private function _orderBy(array $expressions) { $this->select->orderBy($expressions); return $this; }
	
	
	/**
	 * @param mixed $mapper
	 */
	public function __construct($mapper)
	{
		$this->selector = new ObjectQuerySelector($mapper);
	}
	
	
	/**
	 * @param IMySqlConnector $connector
	 * @return IConnector|static
	 */
	public function setConnector(IMySqlConnector $connector): IConnector
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
	public function union(ICmdSelect $select, $all = false) { $this->select->union($select, $all); return $this; }
	public function unionAll(ICmdSelect $select) { $this->select->unionAll($select); return $this; }
	
	public function where($exp, $bind = false) { $this->select->where($exp, $bind); return $this; }
	public function limit($from, $count) { $this->select->limit($from, $count); return $this; }
	
	/**
	 * @return mixed
	 */
	public function queryAll()
	{
		return $this->selector->all($this->select);
	}

	/**
	 * @return mixed
	 */
	public function queryFirst()
	{
		return $this->selector->first($this->select);
	}

	/**
	 * @return mixed
	 */
	public function queryOne()
	{
		return $this->selector->one($this->select);
	}

	/**
	 * @param callable $callback Called for each selected row.
	 * If callback returns false, queryWithCallback will abort and return false.
	 * If callback returns 0, queryWithCallback will abort and return true.
	 * For any other value, callback will continue to the next row.
	 * @return bool
	 */
	public function queryWithCallback($callback)
	{
		return $this->selector->withCallback($this->select, $callback);
	}

	/**
	 * Return an iterator to iterate over all found objects.
	 * @return iterable
	 */
	public function queryIterator(): iterable
	{
		return $this->selector->iterator($this->select);
	}

	/**
	 * Return an array where the result of one column is the index and loaded object is the value.
	 * @param string $key Name of the key column.
	 * @param bool $removeColumnFromRow Should remove the key column from values before converting them to objects.
	 * @return array|false
	 */
	public function queryMapRow(string $key, $removeColumnFromRow = false)
	{
		return $this->selector->iterator($this->select);
	}
	
	
	public function __clone()
	{
		$this->select = clone $this->select;
	}
	
	public function __toString()
	{
		return (string)$this->select;
	}
}