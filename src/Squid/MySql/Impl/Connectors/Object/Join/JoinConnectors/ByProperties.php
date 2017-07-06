<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\JoinConnectors;


use Squid\Exceptions\SquidUsageException;
use Squid\MySql\Connectors\Object\Join\IJoinConnector;
use Squid\MySql\Connectors\Object\CRUD\ID\IIdSave;
use Squid\MySql\Connectors\Object\Generic\IGenericIdentityConnector;


class ByProperties implements IJoinConnector
{
	/** @var IGenericIdentityConnector */
	private $connector;
	
	/** @var string */
	private $parentReferenceProperty;
	
	/** @var array */
	private $childPropertyToField;
	
	/** @var array Parent to child properties */
	private $properties;
	
	
	private function getConnector(): IGenericIdentityConnector
	{
		if (is_string($this->connector))
			$this->connector = \Squid::skeleton($this->connector);
		
		return $this->connector;
	}
	
	private function resolveNames($names): array
	{
		if (is_string($names))
			return [$names => $names];
		else if (isset($names[0]))
			return array_combine($names, $names);
		else
			return $names;
	}
	
	
	public function setProperties($properties): ByProperties
	{
		$this->properties = $this->resolveNames($properties);
		
		if (!$this->childPropertyToField)
			$this->setChildPropertyFieldNames(array_values($this->properties));
		
		return $this;
	}
	
	public function setChildPropertyFieldNames($names): ByProperties
	{
		$this->childPropertyToField = $this->resolveNames($names);
		return $this;
	}
	
	public function setParentReferenceProperty(string $name): ByProperties
	{
		$this->parentReferenceProperty = $name;
		return $this;
	}
	
	public function setConnector($connector): ByProperties
	{
		$this->connector = $connector;
		return $this;
	}
	
	

	/**
	 * @param mixed|array $parents
	 * @return mixed|false
	 */
	public function loaded($parents)
	{
		$target = is_array($parents) ? $parents : [$parents];
		
		$map = [];
		$where = [];
		
		$lastInMap	= null;
		$parentsMap = [];
		
		foreach ($this->properties as $childProp)
		{
			$map[$childProp] = [];
		}
		
		foreach ($target as $parent)
		{
			$key = '';
			
			foreach ($this->properties as $parentProp => $childProp)
			{
				$map[$childProp][] = $parent->$parentProp;
				$key .= '_' . $parentProp . $parent->$parentProp;
			}
			
			$parentsMap[$key] = $parent;
		}
		
		foreach ($this->childPropertyToField as $prop => $field)
		{
			$where[$field] = $map[$prop];
		}
		
		$result = $this->getConnector()->selectObjectsByFields($where);
		
		if ($result === false)
			return false;
		
		foreach ($result as $child)
		{
			$key = '';
			
			foreach ($this->properties as $parentProp => $childProp)
			{
				$map[$childProp] = $child->$childProp;
				$key .= '_' . $parentProp . $child->$childProp;
			}
			
			if (isset($parentsMap[$key]))
			{
				$parentsMap[$key]->{$this->parentReferenceProperty} = $child;
			}
		}
		
		
		return $parents;
	}

	private function updateChildren(array $parents): array
	{
		$modified = [];
		
		foreach ($parents as $parent)
		{
			$child = $parent->{$this->parentReferenceProperty};
			
			if ($child) 
			{
				foreach ($this->properties as $parentProp => $childProp)
				{
					$child->$childProp = $parent->$parentProp;
				}
				
				$modified[] = $child;
			}
		}
		
		return $modified;
	}
	
	/**
	 * @param mixed|array $parents
	 * @param bool $ignore
	 * @return false|int
	 */
	public function inserted($parents, $ignore = false)
	{
		if (!is_array($parents))
			$parents = [$parents];
		
		$modified = $this->updateChildren($parents);
		
		if (!$modified)
		{
			return 0;
		}
		else if ($ignore)
		{
			return $this->getConnector()->insertObjects($modified, $ignore);
		}
		else
		{
			return $this->getConnector()->insert($modified);
		}
	}

	/**
	 * @param mixed $parent
	 * @return int|false
	 */
	public function updated($parent)
	{
		$modified = $this->updateChildren([$parent]);
		return ($modified ? $this->getConnector()->update($modified[0]) : 0);
	}

	/**
	 * @param mixed|array $parents
	 * @return int|false
	 */
	public function upserted($parents)
	{
		if (!is_array($parents))
			$parents = [$parents];
		
		$modified = $this->updateChildren($parents);
		
		return ($modified ? $this->getConnector()->upsert($modified) : 0);
	}

	/**
	 * @param mixed|array $parents
	 * @return int|false
	 */
	public function saved($parents)
	{
		if (!is_array($parents))
			$parents = [$parents];
		
		$connector = $this->getConnector();
		
		if (!($connector instanceof IIdSave))
			throw new SquidUsageException('In order to use the save operation on a OneToOne connector, ' . 
				'the referenced object\'s connector must also have the save method (see: IIdSave interface)');
		
		$modified = $this->updateChildren($parents);
		
		return ($modified ? $connector->save($modified) : 0);
		
	}
}