<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\JoinConnectors;


use Squid\MySql\Connectors\Object\Join\IJoinConnector;
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

	/**
	 * @param mixed|array $parents
	 * @param string $method
	 * @return false|int
	 */
	private function saved($parents, string $method)
	{
		if (!is_array($parents))
			$parents = [$parents];
		
		$modified = [];
		
		foreach ($parents as $parent)
		{
			$child = $parent->{$this->parentReferenceProperty};
				
			if (!$child) continue;
			
			$isModified = false;
			
			foreach ($this->properties as $parentProp => $childProp)
			{
				if ($parent->$parentProp == $child->$childProp) continue;
				
				$child->$childProp = $parent->$parentProp;
				$isModified = true;
			}
			
			if ($isModified)
			{
				$modified[] = $child;
			}
		}
		
		return ($modified ? $this->getConnector()->$method($modified) : 0);
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

	/**
	 * @param mixed|array $parents
	 * @return int|false
	 */
	public function inserted($parents, $ignore = false)
	{
		if (!is_array($parents))
			$parents = [$parents];
		
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
		
		return ($modified ? $this->getConnector()->insertObjects($modified, $ignore) : 0);
	}

	/**
	 * @param mixed|array $parents
	 * @return int|false
	 */
	public function updated($parents)
	{
		return $this->saved($parents, 'upsert');
	}

	/**
	 * @param mixed|array $parents
	 * @return int|false
	 */
	public function upserted($parents)
	{
		return $this->saved($parents, 'upsert');
	}
}