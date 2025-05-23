<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Command\IWithLimit;
use Squid\MySql\Command\IMySqlCommandConstructor;


class CmdSelect extends PartsCommand implements ICmdSelect
{
	use \Squid\MySql\Impl\Traits\CmdTraits\TQuery;
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithWhere;
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithLimit;
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithColumn;
	use \Squid\MySql\Impl\Traits\CmdTraits\TWithExtendedWhere;
	
	
	const PART_WITH			= 0;
	const PART_DISTINCT		= 1;
	const PART_COLUMNS		= 2;
	const PART_FROM			= 3;
	const PART_WHERE		= 4;
	const PART_GROUP_BY		= 5;
	const PART_WITH_ROLL_UP	= 6;
	const PART_HAVING		= 7;
	const PART_ORDER_BY		= 8;
	const PART_LIMIT		= 9;
	const PART_UNION		= 10;
	const PART_LOCK			= 11;
	
	
	/**
	 * @var array Collection of default values.
	 */
	private static $DEFAULT = array(
		CmdSelect::PART_WITH			=> false,
		CmdSelect::PART_DISTINCT		=> false,
		CmdSelect::PART_COLUMNS			=> false,
		CmdSelect::PART_FROM			=> false,
		CmdSelect::PART_WHERE			=> false,
		CmdSelect::PART_GROUP_BY		=> false,
		CmdSelect::PART_WITH_ROLL_UP	=> false,
		CmdSelect::PART_HAVING			=> false,
		CmdSelect::PART_ORDER_BY		=> false,
		CmdSelect::PART_LIMIT			=> false,
		CmdSelect::PART_UNION			=> false,
		CmdSelect::PART_LOCK			=> false
	);
	
	
	/**
	 * @param IMySqlCommandConstructor $from
	 * @param string $alias
	 * @param string $condition
	 * @param string $join
	 * @return static
	 */
	private function fromSubQuery(IMySqlCommandConstructor $from, $alias, $condition = '', $join = '')
	{
		if ($join) $join .= ' ';
		
		$cmd = '';
		
		if ($alias)
			$cmd .= " $alias";
		
		if ($condition)
			$cmd .= " ON $condition";
		
		$sql = $from->assemble();
		
		return $this->appendPart(
			CmdSelect::PART_FROM,
			"$join($sql)$cmd",
			$from->bind()
		);
	}
	
	/**
	 * @param string|IMySqlCommandConstructor $joinWith
	 * @param string $alias
	 * @param string $condition
	 * @param array|bool $bind
	 * @param string $join
	 * @param bool $escape
	 * @return static
	 */
	private function joinWith($joinWith, $alias, $condition, $bind, $join, bool $escape = true)
	{
		if ($joinWith instanceof IMySqlCommandConstructor)
		{
			return $this->fromSubQuery($joinWith, $alias, $condition, $join);
		}
		else
		{
			if ($escape)
			{
				$joinWith = "`{$joinWith}`";
			}
		}
		
		return $this->appendPart(
			CmdSelect::PART_FROM,
			"$join $joinWith $alias ON $condition",
			$bind
		);
	}
	
	/**
	 * @see \Squid\MySql\Impl\Traits\CmdTraits\TWithColumn
	 * @param string[] $columns
	 * @param array|false $bind
	 * @return static
	 */
	protected function addColumn($columns, $bind)
	{
		$this->appendPart(CmdSelect::PART_COLUMNS, $columns, $bind);
		return $this;
	}
	
	
	/**
	 * Get the parts this query can have.
	 * @return array Array containing only the part as keys and values set to false.
	 */
	protected function getDefaultParts()
	{
		return CmdSelect::$DEFAULT;
	}
	
	/**
	 * Combine all the parts into one sql.
	 * @return string Sql query
	 */
	protected function generate()
	{
		$command = '';
		$with = $this->getPart(CmdSelect::PART_WITH);
		
		if ($with)
		{
			$command .= 'WITH' . Assembly::append('', $with);
		}
		
		$command .=
			'SELECT ' .
			($this->getPart(CmdSelect::PART_DISTINCT) ? 'DISTINCT ' : '').
			Assembly::appendDirectly(($this->getPart(CmdSelect::PART_COLUMNS) ?: array('*')), ',');
		
		$union = $this->getPart(CmdSelect::PART_UNION);
		$from = $this->getPart(CmdSelect::PART_FROM);
		
		if (!$from && !$union)
		{
			return $command;
		}
		
		$lock = $this->getPart(CmdSelect::PART_LOCK);
		
		$command .=
			Assembly::append('FROM', $from, ' ') .
			Assembly::appendWhere($this->getPart(CmdSelect::PART_WHERE)) .
			Assembly::append('GROUP BY', $this->getPart(CmdSelect::PART_GROUP_BY)) .
			($this->getPart(CmdSelect::PART_WITH_ROLL_UP) ? 'WITH ROLLUP ' : '') .
			Assembly::append('HAVING', $this->getPart(CmdSelect::PART_HAVING)) .
			Assembly::append('ORDER BY', $this->getPart(CmdSelect::PART_ORDER_BY)) .
			Assembly::append('LIMIT', $this->getPart(CmdSelect::PART_LIMIT));
		
		if ($union)
		{
			$union = implode(' ', $union);
			$command = "($command) $union";
		}
		
		return $command . ($lock ? "$lock " : '');
	}
	
	
	public function debug(): array
	{
		return [
			$this->assemble(),
			$this->bind()
		];
	}
	
	
	/**
	 * @param bool $distinct
	 * @return static
	 */
	public function distinct(bool $distinct = true)
	{
		return $this->setPart(CmdSelect::PART_DISTINCT, $distinct);
	}
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string|null $alias
	 * @param bool $escape
	 * @return static
	 */
	public function from($table, ?string $alias = null, bool $escape = true)
	{
		if ($table instanceof IMySqlCommandConstructor)
		{
			$this->setPart(CmdSelect::PART_FROM, false);
			return $this->fromSubQuery($table, $alias);
		}
		else
		{
			if ($escape)
			{
				$table = "`{$table}`";
			}
		}
		
		if ($alias)
		{
			$table = "$table $alias";
		}
		
		return $this->setPart(CmdSelect::PART_FROM, [$table]);
	}
	
	/**
	 * @param ICmdSelect $select
	 * @param string $alias
	 * @return static
	 */
	public function with(ICmdSelect $select, string $alias)
	{
		return $this->appendPart(
			CmdSelect::PART_WITH,
			$alias . " AS ({$select->assemble()})",
			$select->bind()
		);
	}
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @param bool $escape
	 * @return static
	 */
	public function join($table, string $alias, string $condition, $bind = [], bool $escape = true)
	{
		return $this->joinWith($table, $alias, $condition, $bind, 'JOIN', $escape);
	}
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @param bool $outer
	 * @param bool $escape
	 * @return static
	 */
	public function leftJoin(
		$table,
		string $alias,
		string $condition,
		$bind = [],
		bool $outer = false,
		bool $escape = true)
	{
		return $this->joinWith(
			$table, $alias, $condition, $bind,
			($outer ? 'LEFT OUTER JOIN' : 'LEFT JOIN'), $escape);
	}
	
	/**
	 * @param string|IMySqlCommandConstructor $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @param bool $outer
	 * @param bool $escape
	 * @return static
	 */
	public function rightJoin(
		$table,
		string $alias,
		string $condition,
		$bind = [],
		bool $outer = false,
		bool $escape = true)
	{
		return $this->joinWith(
			$table, $alias, $condition, $bind,
			($outer ? 'RIGHT OUTER JOIN' : 'RIGHT JOIN'), $escape);
	}
	
	
	/**
	 * @param string|array $column
	 * @param array|bool $bind
	 * @return static
	 */
	public function groupBy($column, $bind = [])
	{
		if (is_array($column)) $column = implode(',', $column);
		
		return $this->appendPart(CmdSelect::PART_GROUP_BY, $column, $bind);
	}
	
	/**
	 * @param string $exp
	 * @param mixed|array $bind
	 * @return static
	 */
	public function having(string $exp, $bind = [])
	{
		return $this->appendPart(CmdSelect::PART_HAVING, $exp, $bind);
	}
	
	/**
	 * @param array $expressions
	 * @return mixed
	 */
	public function _orderBy(array $expressions)
	{
		return $this->appendPart(CmdSelect::PART_ORDER_BY, $expressions);
	}
	
	/**
	 * @param bool $withRollup
	 * @return static
	 */
	public function withRollup(bool $withRollup = true)
	{
		return $this->setPart(CmdSelect::PART_WITH_ROLL_UP, $withRollup);
	}
	
	/**
	 * @param ICmdSelect $select
	 * @param bool $all
	 * @return static
	 */
	public function union(IMySqlCommandConstructor $select, bool $all = false)
	{
		$union = 'UNION ' . ($all ? 'ALL ' : '');
		
		return $this->appendPart(
			CmdSelect::PART_UNION,
			$union . "({$select->assemble()})",
			$select->bind());
	}
	
	/**
	 * @param ICmdSelect $select
	 * @return static
	 */
	public function unionAll(IMySqlCommandConstructor $select)
	{
		return $this->union($select, true);
	}
	
	/**
	 * @param bool $forUpdate
	 * @return static
	 */
	public function forUpdate(bool $forUpdate = true)
	{
		return $this->setPart(CmdSelect::PART_LOCK,
			($forUpdate ? 'FOR UPDATE' : false));
	}
	
	/**
	 * @param bool $lockInShareMode
	 * @return static
	 */
	public function lockInShareMode(bool $lockInShareMode = true)
	{
		return $this->setPart(CmdSelect::PART_LOCK,
			($lockInShareMode ? 'LOCK IN SHARE MODE' : false));
	}
	
	/**
	 * @param string $exp
	 * @param mixed|array|null $bind
	 * @return static
	 */
	public function where(string $exp, $bind = [])
	{
		return $this->appendPart(CmdSelect::PART_WHERE, $exp, $bind);
	}
	
	/**
	 * @param int $from
	 * @param int $count
	 * @return IWithLimit|static
	 */
	public function limit($from, $count): IWithLimit
	{
		return $this->setPart(CmdSelect::PART_LIMIT, [(int)$from, (int)$count], []);
	}
	
	/**
	 * @return bool|null
	 */
	public function queryExists()
	{
		$cmdExists = new CmdSelect();
		$cmdExists->setConnection($this->getConn());
		$cmdExists->setPart(
			CmdSelect::PART_COLUMNS,
			['EXISTS (' . $this->assemble() . ')'],
			$this->bind());
		
		$result = $cmdExists->queryScalar(null);
		
		return (is_null($result) ? null : (bool)$result);
	}
	
	/**
	 * @return int|bool|null
	 */
	public function queryCount()
	{
		$union		= $this->getPart(CmdSelect::PART_UNION);
		$distinct	= $this->getPart(CmdSelect::PART_DISTINCT);
		
		if ($union || $distinct)
		{
			$countSelect = new CmdSelect();
			$countSelect->setConnection($this->getConn());
			$countSelect->columnsExp('COUNT(*)')->from($this, 'a');
			return $countSelect->queryInt();
		}
		
		$select = clone $this;
		
		$groupBy		= $this->getPart(CmdSelect::PART_GROUP_BY);
		$groupByBinds	= $this->getBind(CmdSelect::PART_GROUP_BY);
		
		if ($groupBy)
		{
			$groupByQuery = implode(',', $groupBy);
			
			$select->setPart(CmdSelect::PART_COLUMNS, array("COUNT(DISTINCT $groupByQuery)"), $groupByBinds);
			$select->setPart(CmdSelect::PART_GROUP_BY, false);
		}
		else
		{
			$select->setPart(CmdSelect::PART_COLUMNS, array('COUNT(*)'));
		}
		
		$select->distinct(false);
		$select->forUpdate(false);
		$select->lockInShareMode(false);
		$select->setPart(CmdSelect::PART_ORDER_BY, '', false);
		
		return $select->queryInt();
	}
	
	
	public function __clone() {}
}