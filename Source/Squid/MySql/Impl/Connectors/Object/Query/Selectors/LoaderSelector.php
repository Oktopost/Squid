<?php
namespace Squid\MySql\Impl\Connectors\Object\Query\Selectors;


use Squid\MySql\Impl\Connectors\Object\Query\Selectors\Decorator\IObjectLoader;
use Squid\MySql\Command\ICmdSelect;
use Squid\MySql\Connectors\Object\Query\IObjectSelector;

use Structura\Map;


class LoaderSelector implements IObjectSelector
{
	/** @var IObjectSelector */
	private $selector;
	
	/** @var IObjectLoader */
	private $loader;
	
	
	private function loadOne($object)
	{
		if ($object)
		{
			$this->loader->loadOne($object);
		}
		
		return $object;
	}
	
	private function loadAll(array $objects)
	{
		if ($objects)
		{
			$this->loader->loadAll($objects);
		}
		
		return $objects;
	}
	
	
	/**
	 * @param IObjectSelector|mixed $selector
	 * @return static
	 */
	public function setSelector($selector)
	{
		if ($selector instanceof IObjectSelector)
		{
			$this->selector = $selector;
		}
		else
		{
			$this->selector = new StandardSelector();
			$this->selector->setObjectMap($selector);
		}
		
		return $this;
	}

	/**
	 * @param IObjectLoader $loader
	 * @return static
	 */
	public function setLoader(IObjectLoader $loader)
	{
		$this->loader = $loader;
		return $this;
	}
	
	
	/**
	 * @param ICmdSelect $select
	 * @return array|false
	 */
	public function all(ICmdSelect $select)
	{
		$objects = $this->selector->all($select);
		return $this->loadAll($objects);
	}

	/**
	 * @param ICmdSelect $select
	 * @return mixed|false
	 */
	public function one(ICmdSelect $select)
	{
		$objects = $this->selector->one($select);
		return $this->loadOne($objects);
	}

	/**
	 * @param ICmdSelect $select
	 * @return mixed|false
	 */
	public function first(ICmdSelect $select)
	{
		$objects = $this->selector->first($select);
		return $this->loadOne($objects);
	}

	/**
	 * @param ICmdSelect $select
	 * @param callable $callback
	 * @return bool
	 */
	public function withCallback(ICmdSelect $select, callable $callback): bool
	{
		return $this->selector->withCallback($select, $callback);
	}

	/**
	 * @param ICmdSelect $select
	 * @return iterable
	 */
	public function iterator(ICmdSelect $select): iterable
	{
		return $this->selector->iterator($select);
	}

	/**
	 * @param ICmdSelect $select
	 * @param string $field
	 * @param bool $removeColumnFromRow
	 * @return array|false
	 */
	public function map(ICmdSelect $select, string $field, bool $removeColumnFromRow = false)
	{
		return $this->selector->map($select, $field, $removeColumnFromRow);
	}
	
	/**
	 * @param ICmdSelect $select
	 * @param string|int $byColumn
	 * @param bool $removeColumn
	 * @return Map
	 */
	public function groupBy(ICmdSelect $select, $byColumn, bool $removeColumn = false): Map
	{
		return $this->selector->groupBy($select, $byColumn, $removeColumn);
	}
}