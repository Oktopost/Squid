<?php
namespace Squid\MySql\Impl\Connectors\Object\Query;


use Structura\Map;

use Squid\MySql\Connectors\Map\IRowMap;
use Squid\MySql\Connectors\Object\Query\IObjectSelector;
use Squid\MySql\Connectors\Object\Query\ICmdObjectSelect;

use Squid\MySql\Impl\Traits\CmdTraits\TWithLimit;
use Squid\MySql\Impl\Traits\CmdTraits\TWithColumn;
use Squid\MySql\Impl\Traits\CmdTraits\Decorators\TWithWhereDecorated;

use Squid\MySql\Impl\Connectors\Utils\Select\SelectDecorator;
use Squid\MySql\Impl\Connectors\Object\Query\Selectors\StandardSelector;


class CmdObjectSelect extends SelectDecorator implements ICmdObjectSelect
{
	use TWithLimit;
	use TWithColumn;
	use TWithWhereDecorated;
	
	
	/** @var IObjectSelector */
	private $selector;
	
	
	/**
	 * @param IObjectSelector|IRowMap $selector
	 */
	public function __construct($selector)
	{
		parent::__construct();
		
		if ($selector instanceof IRowMap)
		{
			$selector = new StandardSelector($selector);
		}
		
		$this->selector = $selector;
	}

	
	/**
	 * @return mixed
	 */
	public function queryAll()
	{
		return $this->selector->all($this->getSelect());
	}

	/**
	 * @return mixed
	 */
	public function queryFirst()
	{
		return $this->selector->first($this->getSelect());
	}

	/**
	 * @return mixed
	 */
	public function queryOne()
	{
		return $this->selector->one($this->getSelect());
	}

	/**
	 * @param callable $callback Called for each selected row.
	 * If callback returns false, queryWithCallback will abort and return false.
	 * If callback returns 0, queryWithCallback will abort and return true.
	 * For any other value, callback will continue to the next row.
	 * @return bool
	 */
	public function queryWithCallback(callable $callback)
	{
		return $this->selector->withCallback($this->getSelect(), $callback);
	}

	/**
	 * Return an iterator to iterate over all found objects.
	 * @return iterable
	 */
	public function queryIterator(): iterable
	{
		return $this->selector->iterator($this->getSelect());
	}

	/**
	 * Return an array where the result of one column is the index and loaded object is the value.
	 * @param string $key Name of the key column.
	 * @param bool $removeColumnFromRow Should remove the key column from values before converting them to objects.
	 * @return array|false
	 */
	public function queryMapRow(string $key, $removeColumnFromRow = false)
	{
		return $this->selector->map($this->getSelect(), $key, $removeColumnFromRow);
	}
	
	/**
	 * Return array where each value is an array of rows grouped by a single column.
	 * @param string|int $byColumn Column to group by.
	 * @param bool $removeColumn If set to true, the group by column is removed from the row.
	 * @return Map
	 */
	public function queryGroupBy($byColumn, bool $removeColumn = false): Map
	{
		return $this->selector->groupBy($this->getSelect(), $byColumn, $removeColumn);
	}
	
	
	public function __clone()
	{
		parent::__clone();
		$this->selector = clone $this->selector;
	}
}