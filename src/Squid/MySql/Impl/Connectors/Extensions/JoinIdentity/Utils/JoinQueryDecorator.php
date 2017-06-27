<?php
namespace Squid\MySql\Impl\Connectors\Extensions\JoinIdentity\Utils;


use Squid\MySql\Connectors\Object\ObjectSelect\ICmdObjectSelect;
use Squid\MySql\Impl\Connectors\Extensions\Select\SelectDecorator;


class JoinQueryDecorator extends SelectDecorator implements ICmdObjectSelect
{
	/** @var ICmdObjectSelect */
	private $objectSelect;
	
	/** @var IJoinedDataLoader */
	private $loader;
	
	
	public function __construct(ICmdObjectSelect $from, IJoinedDataLoader $loader)
	{
		parent::__construct($from);
		
		$this->objectSelect = $from;
		$this->loader = $loader;
	}


	/**
	 * @return mixed
	 */
	public function queryAll()
	{
		$data = $this->objectSelect->queryAll();
		
		if ($data)
			$this->loader->loadData($data);
		
		return $data;
	}

	/**
	 * @return mixed
	 */
	public function queryFirst()
	{
		$data = $this->objectSelect->queryFirst();
		
		if ($data)
			$this->loader->loadData($data);
		
		return $data;
	}

	/**
	 * @return mixed
	 */
	public function queryOne()
	{
		$data = $this->objectSelect->queryOne();
		
		if ($data)
			$this->loader->loadData($data);
		
		return $data;
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
		return $this->objectSelect->queryWithCallback(
			function($data)
				use ($callback)
			{
				if (!$this->loader->loadData($data))
				{
					return false;
				}
				
				return $callback($data);
			}
		);
	}

	/**
	 * Return an iterator to iterate over all found objects.
	 */
	public function queryIterator(): iterable
	{
		foreach ($this->objectSelect->queryIterator() as $item)
		{
			$this->loader->loadData($item);
			yield $item;
		}
	}

	/**
	 * Return an array where the result of one column is the index and loaded object is the value.
	 * @param string $key Name of the key column.
	 * @param bool $removeColumnFromRow Should remove the key column from values before converting them to objects.
	 * @return array|false
	 */
	public function queryMapRow(string $key, $removeColumnFromRow = false)
	{
		$map = $this->queryMapRow($key, $removeColumnFromRow);
		
		if ($map)
		{
			$values = array_values($map);
			
			if (!$this->loader->loadData($values))
			{
				$map = false;
			}
		}
		
		return $map;
	}
}