<?php
namespace Squid\MySql\Impl\Connectors\Objects\Join\Selector;


use Squid\Exceptions\SquidException;
use Squid\MySql\Connectors\Objects\Join\IJoinConnector;
use Squid\MySql\Connectors\Objects\Query\ICmdObjectSelect;

use Squid\MySql\Impl\Connectors\Utils\Select\SelectDecorator;

use Structura\Map;


class JoinedObjectSelect extends SelectDecorator implements ICmdObjectSelect
{
	/** @var ICmdObjectSelect */
	private $child;
	
	/** @var IJoinConnector */
	private $config;
	
	
	public function __construct(ICmdObjectSelect $child, IJoinConnector $config)
	{
		parent::__construct($child);
		$this->config = $config;
		$this->child = $child;
	}


	/**
	 * @return mixed
	 */
	public function queryAll()
	{
		$data = $this->child->queryAll();
		return ($data ? $this->config->loaded($data) : $data);
	}

	/**
	 * @return mixed
	 */
	public function queryFirst()
	{
		$data = $this->child->queryFirst();
		return ($data ? $this->config->loaded($data) : $data);
	}

	/**
	 * @return mixed
	 */
	public function queryOne()
	{
		$data = $this->child->queryFirst();
		return ($data ? $this->config->loaded($data) : $data);
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
		throw new SquidException('queryWithCallback is not supported for joined table');
	}

	/**
	 * Return an iterator to iterate over all found objects.
	 */
	public function queryIterator(): iterable
	{
		throw new SquidException('queryIterator is not supported for joined table');
	}

	/**
	 * Return an array where the result of one column is the index and loaded object is the value.
	 * @param string $key Name of the key column.
	 * @param bool $removeColumnFromRow Should remove the key column from values before converting them to objects.
	 * @return array|false
	 */
	public function queryMapRow(string $key, $removeColumnFromRow = false)
	{
		$data = $this->child->queryMapRow($key, $removeColumnFromRow);
		
		if ($data)
		{
			$this->config->loaded($data);
		}
		
		return $data;
	}
	
	/**
	 * Return array where each value is an array of rows grouped by a single column.
	 * @param string|int $byColumn Column to group by.
	 * @param bool $removeColumn If set to true, the group by column is removed from the row.
	 * @return Map
	 */
	public function queryGroupBy($byColumn, bool $removeColumn = false): Map
	{
		$map = $this->child->queryGroupBy($byColumn, $removeColumn);
		
		if ($map->hasElements())
		{
			$allGroups = $map->toArray();
			$allObjects = array_merge(...$allGroups);
			
			$this->config->loaded($allObjects);
		}
		
		return $map;
	}
}