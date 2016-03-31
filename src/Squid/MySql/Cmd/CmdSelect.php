<?php
namespace Squid\MySql\Cmd;


use \Squid\Common;
use \Squid\Base\Cmd\ICmdSelect;


class CmdSelect extends PartsCommand implements ICmdSelect {
	use Squid\MySql\Traits\CmdTraits\TQuery;
	use Squid\MySql\Traits\CmdTraits\TWithWhere;
	use Squid\MySql\Traits\CmdTraits\TWithLimit;
	
	
	const PART_DISTINCT		= 0;
	const PART_COLUMNS		= 1;
	const PART_FROM			= 2;
	const PART_WHERE		= 3;
	const PART_GROUP_BY		= 4;
	const PART_WITH_ROLL_UP	= 5;
	const PART_HAVING		= 6;
	const PART_ORDER_BY		= 7;
	const PART_LIMIT		= 8;
	const PART_UNION		= 9;
	const PART_LOCK			= 10;
	
	
	/**
	 * @var array Collection of default values.
	 */
	private static $DEFAULT = array(
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
	 * @return array
	 */
	protected function getDefaultParts() {
		return CmdSelect::$DEFAULT;
	}
	
	/**
	 * @return string
	 */
	protected function generate() {
		$command = 
			'SELECT ' . 
				($this->getPart(CmdSelect::PART_DISTINCT) ? 'DISTINCT ' : ''). 
				Assembly::appendDirect(($this->getPart(CmdSelect::PART_COLUMNS) ?: array('*')), ',');
		
		$union = $this->getPart(CmdSelect::PART_UNION);
		$from = $this->getPart(CmdSelect::PART_FROM);
		
		if (!$from && !$union) {
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
			Assembly::appendLimit(
					$this->getPart(CmdSelect::PART_LIMIT), 
					$this->getBind(CmdSelect::PART_LIMIT));
		
		if ($union) {
			$union = implode(' ', $union);
			$command = "($command) $union";
		}
		
		return $command . ($lock ? "$lock " : '');
	}
	
	
	/**
	 * @param bool $distinct
	 * @return ICmdSelect
	 */
	public function distinct($distinct = true) {
		return $this->setPart(CmdSelect::PART_DISTINCT, $distinct);
	}
	
	/**
	 * @param string|array $columns
	 * @param string|bool $table
	 * @return ICmdSelect
	 */
	public function columns($columns, $table = false) {
		Common::toArray($columns);
		
		if ($table) {
			foreach ($columns as &$column) {
				$column = "`$table`.`$column`";
			}
		}
		
		return $this->appendPart(CmdSelect::PART_COLUMNS, $columns);
	}
	
	/**
	 * @param string|array $columns
	 * @param bool|array $bind
	 * @return ICmdSelect
	 */
	public function columnsExp($columns, $bind = false) {
		return $this->appendPart(
			CmdSelect::PART_COLUMNS, 
			Common::toArray($columns), 
			$bind);
	}
	
	/**
	 * @param string $column
	 * @param string $alias
	 */
	public function columnAs($column, $alias) {
		return $this->appendPart(
			CmdSelect::PART_COLUMNS, 
			"$column as $alias");
	}
	
	/**
	 * @param string $column
	 * @param string $alias
	 * @param array $bind
	 * @return ICmdSelect
	 */
	public function columnAsExp($column, $alias, $bind = false) {
		return $this->appendPart(
			CmdSelect::PART_COLUMNS, 
			"$column as $alias", 
			$bind);
	}
	
	/**
	 * @param string|ICmdSelect
	 * @param string $alias
	 * @return ICmdSelect
	 */
	public function from($table, $alias = false) {
		if ($table instanceof ICmdSelect) {
			$this->setPart(CmdSelect::PART_FROM, false);
			
			return $this->fromSubQuery($table, $alias);
		}
		
		if ($alias) {
			$table = "$table $alias";
		}
		
		return $this->setPart(CmdSelect::PART_FROM, array($table));
	}
	
	/**
	 * @param string|ICmdSelect $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @return ICmdSelect
	 */
	public function join($table, $alias, $condition, $bind = false) {
		return $this->joinWith($table, $alias, $condition, $bind, 'JOIN');
	}
	
	/**
	 * @param string|ICmdSelect $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @param bool $outer
	 * @return ICmdSelect
	 */
	public function leftJoin($table, $alias, $condition, $bind = false, $outer = false) {
		return $this->joinWith(
			$table, $alias, $condition, $bind, 
			($outer ? 'LEFT OUTER JOIN' : 'LEFT JOIN'));
	}
	
	/**
	 * @param string|ICmdSelect $table
	 * @param string $alias
	 * @param string $condition
	 * @param mixed|array $bind
	 * @param bool $outer
	 * @return ICmdSelect
	 */
	public function rightJoin($table, $alias, $condition, $bind = false, $outer = false) {
		return $this->joinWith(
			$table, $alias, $condition, $bind, 
			($outer ? 'RIGHT OUTER JOIN' : 'RIGHT JOIN'));
	}


	/**
	 * @param string|array $column
	 * @param array $bind
	 * @return ICmdSelect
	 */
	public function groupBy($column, $bind = false) {
		return $this->appendPart(CmdSelect::PART_GROUP_BY, Common::toString($column), $bind);
	}
	
	/**
	 * @param string $exp
	 * @param mixed|array|bool $bind
	 * @return ICmdSelect
	 */
	public function having($exp, $bind = false) {
		return $this->appendPart(CmdSelect::PART_HAVING, $exp, $bind);
	}
	
	/**
	 * @param array $columns
	 * @return mixed
	 */
	public function _orderBy(array $columns) {
		return $this->appendPart(CmdSelect::PART_ORDER_BY, $columns);
	}
	
	/**
	 * @param bool $withRollup
	 * @return ICmdSelect
	 */
	public function withRollup($withRollup = true) {
		return $this->setPart(CmdSelect::PART_WITH_ROLL_UP, $withRollup);
	}
	
	/**
	 * @param ICmdSelect $select
	 * @param bool $all
	 * @return ICmdSelect
	 */
	public function union(ICmdSelect $select, $all = false) {
		$union = 'UNION ' . ($all ? 'ALL ' : '');
		
		return $this->appendPart(
			CmdSelect::PART_UNION, 
			$union . "({$select->assemble()})", 
			$select->bindParams());
	}
	
	/**
	 * @param bool $forUpdate
	 * @return ICmdSelect
	 */
	public function forUpdate($forUpdate = true) {
		return $this->setPart(CmdSelect::PART_LOCK, 
			($forUpdate ? 'FOR UPDATE' : false));
	}
	
	/**
	 * @param bool $lockInShareMode
	 * @return ICmdSelect
	 */
	public function lockInShareMode($lockInShareMode = true) {
		return $this->setPart(CmdSelect::PART_LOCK, 
			($lockInShareMode ? 'LOCK IN SHARE MODE' : false));
	}
	
	/**
	 * @param string $exp
	 * @param mixed|array|null $bind
	 * @return ICmdSelect
	 */
	public function where($exp, $bind = false) {
		return $this->appendPart(CmdSelect::PART_WHERE, $exp, $bind); 
	}
	
	/**
	 * @param int $from
	 * @param int $count
	 * @return ICmdSelect
	 */
	public function limit($from, $count) {
		return $this->setPart(CmdSelect::PART_LIMIT, true, ($from ? array($from, $count) : $count));
	}
	
	/**
	 * @return bool|null
	 */
	public function queryExists() {
		$cmdExists = new CmdSelect();
		$cmdExists->setConnection($this->getConn());
		$cmdExists->setPart(
			CmdSelect::PART_COLUMNS, 
			array('EXISTS (' . $this->assemble() . ')'), 
			$this->bindParams());
		
		$result = $cmdExists->queryScalar(null);
		
		return (is_null($result) ? null : (bool)$result);
	}
	
	/**
	 * @return int|bool
	 */
	public function queryCount() {
		$select = clone $this;
		
		$groupBy		= $this->getPart(CmdSelect::PART_GROUP_BY);
		$groupByBinds	= $this->getBind(CmdSelect::PART_GROUP_BY);
		
		if ($groupBy) {
			$groupByQuery = implode(',', $groupBy);
			
			$select->setPart(CmdSelect::PART_COLUMNS, array("COUNT(DISTINCT $groupByQuery)"), $groupByBinds);
			$select->setPart(CmdSelect::PART_GROUP_BY, false);
		} else {
			$select->setPart(CmdSelect::PART_COLUMNS, array('COUNT(*)'));
		}
		
		$select->distinct(false);
		$select->forUpdate(false);
		$select->lockInShareMode(false);
		$select->setPart(CmdSelect::PART_ORDER_BY, '', false);
		
		return $select->queryInt();
	}
	
	
	/**
	 * @param ICmdSelect $from
	 * @param string $alias
	 * @param string $join
	 * @return ICmdSelect
	 */
	private function fromSubQuery(ICmdSelect $from, $alias, $condition = '', $join = '') {
		if ($join) {
			$join .= ' ';
		}
		
		$cmd = '';
		
		if ($alias) {
			$cmd .= " $alias";
		}
		
		if ($condition) {
			$cmd .= " ON $condition";
		}
		
		$sql = $from->assemble();
		
		return $this->appendPart(
			CmdSelect::PART_FROM, 
			"$join($sql)$cmd", 
			$from->bindParams()
		);
	}
	
	/**
	 * @param string|ICmdSelect $joinWith
	 * @param string $alias
	 * @param string $condition
	 * @param array|bool $bind
	 * @param string $join
	 * @return ICmdSelect
	 */
	private function joinWith($joinWith, $alias, $condition, $bind, $join) {
		if ($joinWith instanceof ICmdSelect) {
			return $this->fromSubQuery($joinWith, $alias, $condition, $join);
		}
		
		return $this->appendPart(
			CmdSelect::PART_FROM, 
			"$join $joinWith $alias ON $condition", 
			$bind
		);
	}
}