<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic;


use Squid\OrderBy;
use Squid\Exceptions\SquidException;
use Squid\Exceptions\SquidDevelopmentException;

use Squid\MySql\Connectors\Object\CRUD\Generic\TObjectSelectHelper;
use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\Polymorphic\IPolymorphicConfig;
use Squid\MySql\Connectors\Object\Polymorphic\IPolymorphicConnector;

use Squid\MySql\Connectors\Generic\TCountHelper;
use Squid\MySql\Connectors\Generic\TDeleteHelper;


class PolymorphicConnector implements IPolymorphicConnector
{
	use TCountHelper;
	use TDeleteHelper;
	use TObjectSelectHelper;
	
	
	/** @var IPolymorphicConfig */
	private $config = null; 
	
	
	private function executeDmlOperationOnObjects($objects, $callback)
	{
		$count = 0;
		
		if (!is_array($objects))
			$objects = [$objects];
		
		foreach ($this->config->objectsIterator($objects) as $name => $items)
		{
			$connector = $this->config->getConnector($name);
			$result = $callback($connector, $items);
			
			if ($result === false)
				return false;
			
			$count += $result;
		}
		
		return $count;
	}
	
	
	protected function getConfig(): IPolymorphicConfig
	{
		return $this->config;
	}
	

	/**
	 * @param IPolymorphicConfig $config
	 * @return static
	 */
	public function setPolymorphicConfig(IPolymorphicConfig $config)
	{
		$this->config = $config;
		return $this;
	}
	

	/**
	 * @param array $fields
	 * @return int|false
	 */
	public function countByFields(array $fields)
	{
		$totalCount = 0;
		
		foreach ($this->config->expressionsIterator($fields) as $name => $expression)
		{
			$connector = $this->config->getConnector($name);
			$count = $connector->countByFields($expression);
			
			if ($count === false)
				return false;
			
			$totalCount += $count;
		}
		
		return $totalCount;
	}

	/**
	 * @param array $fields
	 * @return bool
	 */
	public function existsByFields(array $fields): bool
	{
		foreach ($this->config->expressionsIterator($fields) as $name => $expression)
		{
			$connector = $this->config->getConnector($name);
			
			if ($connector->existsByFields($expression))
				return true;
		}
		
		return false;
	}

	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectObjectByFields(array $fields)
	{
		$found = null;
		
		foreach ($this->config->expressionsIterator($fields) as $name => $expression)
		{
			$connector = $this->config->getConnector($name);
			$object = $connector->selectObjectByFields($expression);
			
			if ($object === false)
			{
				return false;
			}
			else if ($object)
			{
				if ($found)
				{
					throw new SquidException('More then one object selected for query!');
				}
				else 
				{
					$found = $object;
				}
					
			}
		}
		
		return $found;
	}

	/**
	 * @param array $fields
	 * @return mixed|null|false
	 */
	public function selectFirstObjectByFields(array $fields)
	{
		$found = null;
		
		foreach ($this->config->expressionsIterator($fields) as $name => $expression)
		{
			$connector = $this->config->getConnector($name);
			$object = $connector->selectFirstObjectByFields($expression);
			
			if ($object || $object === false)
			{
				return $object;
			}
		}
		
		return null;
	}

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return array|false
	 */
	public function selectObjectsByFields(array $fields, ?int $limit = null)
	{
		$foundObjects = [];
		
		foreach ($this->config->expressionsIterator($fields) as $name => $expression)
		{
			$connector = $this->config->getConnector($name);
			$objects = $connector->selectObjectsByFields($expression, $limit);
			
			if ($objects === false)
			{
				return false;
			}
			else if ($objects)
			{
				$foundObjects[] = $objects;	
			}
			
			if (!is_null($limit))
			{
				$limit -= count($objects);
				
				if ($limit <= 0)
				{
					break;
				}
			}
		}
		
		return ($foundObjects ? array_merge(...$foundObjects) : []);
	}
	
	/**
	 * @param array|null $orderBy
	 * @param int $order
	 * @return array|false
	 */
	public function selectObjects(?array $orderBy = null, int $order = OrderBy::DESC)
	{
		if ($orderBy)
		{
			throw new SquidDevelopmentException('selectObjects Operation not ' . 
				'supported by PolyConnector with an orderBy expression');
		}
		
		$foundObjects = [];
		
		foreach ($this->config->getConnectors() as $connector)
		{
			$objects = $connector->selectObjects(null);
			
			if ($objects === false)
			{
				return false;
			}
			else if ($objects)
			{
				$foundObjects[] = $objects;	
			}
		}
		
		return ($foundObjects ? array_merge(...$foundObjects) : []);
	}

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return int|false
	 */
	public function deleteByFields(array $fields, ?int $limit = null)
	{
		$count = 0;
		
		foreach ($this->config->expressionsIterator($fields) as $name => $expression)
		{
			$connector = $this->config->getConnector($name);
			$result = $connector->deleteByFields($expression, $limit);
			
			if ($result === false)
			{
				return false;
			}
			
			$count += $result;
			
			if ($limit)
			{
				$limit -= $result;
				
				if ($limit <= 0)
					break;
			}
		}
		
		return $count;
	}

	/**
	 * @param mixed|array $objects
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insertObjects($objects, bool $ignore = false)
	{
		return $this->executeDmlOperationOnObjects($objects,
			function($connector, $items) 
				use ($ignore)
			{
				/** @var $connector IGenericObjectConnector */
				return $connector->insertObjects($items, $ignore);
			});
	}

	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function updateObject($object, array $byFields)
	{
		$connector = $this->config->getObjectConnector($object);
		return $connector->updateObject($object, $byFields);
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $keys
	 * @return false|int
	 */
	public function upsertObjectsByKeys($objects, array $keys)
	{
		return $this->executeDmlOperationOnObjects($objects,
			function($connector, $items) 
				use ($keys)
			{
				/** @var $connector IGenericObjectConnector */
				return $connector->upsertObjectsByKeys($items, $keys);
			});
	}

	/**
	 * @param mixed|array $objects
	 * @param string[] $valueFields
	 * @return false|int
	 */
	public function upsertObjectsForValues($objects, array $valueFields)
	{
		return $this->executeDmlOperationOnObjects($objects,
			function($connector, $items) 
				use ($valueFields)
			{
				/** @var $connector IGenericObjectConnector */
				return $connector->upsertObjectsForValues($items, $valueFields);
			});
	}
}