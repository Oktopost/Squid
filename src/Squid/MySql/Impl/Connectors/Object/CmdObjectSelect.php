<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\Object\ObjectSelect\IQuerySelector;
use Squid\MySql\Connectors\Object\ObjectSelect\ICmdObjectSelect;

use Squid\MySql\Impl\Traits\CmdTraits\TWithLimit;
use Squid\MySql\Impl\Traits\CmdTraits\TWithWhere;
use Squid\MySql\Impl\Traits\CmdTraits\TWithColumn;
use Squid\MySql\Impl\Connectors\Extensions\Select\SelectDecorator;


class CmdObjectSelect extends SelectDecorator implements ICmdObjectSelect
{
	use TWithWhere;
	use TWithLimit;
	use TWithColumn;
	
	
	/** @var IQuerySelector */
	private $selector;
	
	
	/**
	 * @param mixed $mapper
	 */
	public function __construct($mapper)
	{
		parent::__construct();
		$this->selector = new ObjectQuerySelector($mapper);
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
	public function queryWithCallback($callback)
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
	
	
	public function __clone()
	{
		parent::__clone();
		$this->selector = clone $this->selector;
	}
}