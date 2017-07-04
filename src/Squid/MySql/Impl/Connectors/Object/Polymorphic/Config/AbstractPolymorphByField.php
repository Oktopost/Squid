<?php
namespace Squid\MySql\Impl\Connectors\Object\Polymorphic\Config;


use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\Polymorphic\IPolymorphicConfig;

use Squid\Exceptions\SquidUsageException;


abstract class AbstractPolymorphByField implements IPolymorphicConfig
{
	/** @var IGenericObjectConnector[] */
	private $connectors;
	
	/** @var IGenericObjectConnector[] */
	private $connectorsByClass;


	private function throwUndefinedGroup($field, $value)
	{
		throw new SquidUsageException('Could not determine the right ' . 
			"connector to use for field $field and value $value");
	}
	
	private function getConnectorsByClassCached(): array
	{
		if (!$this->connectorsByClass)
			$this->connectorsByClass = $this->getConnectorsByClass();
		
		return $this->connectorsByClass;
	}
	
	private function resolveArrayRuleForField(array $rule, string $field, $value): array
	{
		if (!is_array($value))
			$value = [$value];
		
		$grouped = [];
		
		foreach ($value as $item)
		{
			if (!isset($rule[$item]))
				$this->throwUndefinedGroup($field, $item);
			
			$group = $rule[$item];
			
			if (!isset($grouped[$group]))
				$grouped[$group] = [$item];
			else
				$grouped[$group][] = $item;
		}
		
		return $grouped;
	}
	
	private function resolveCallableRuleForField(callable $rule, string $field, $value): array
	{
		if (!is_array($value))
			$value = [$value];
		
		$grouped = [];
		
		foreach ($value as $item)
		{
			$group = $rule($field, $item);
			
			if (!isset($grouped[$group]))
				$grouped[$group] = [$item];
			else
				$grouped[$group][] = $item;
		}
		
		return $grouped;
	}
	
	
	/**
	 * @return IGenericObjectConnector[]
	 */
	protected abstract function getConnectorsByClass(): array;

	/**
	 * @return array
	 */
	protected abstract function getByFieldRules(): array;
	
	
	public function getObjectConnector($object): IGenericObjectConnector
	{
		return $this->getConnector(get_class($object));
	}
	
	public function getConnector(string $name): IGenericObjectConnector
	{
		$byClass = $this->getConnectorsByClassCached();
		
		if (!isset($byClass[$name]))
			throw new SquidUsageException('No connector defined for class ' . $name);
		
		$value = $byClass[$name];
		
		if (is_string($value))
		{
			$value = \Squid::skeleton($value);
			$byClass[$name] = $value;
		}
		
		return $value;
	}
	
	/**
	 * @return IGenericObjectConnector[]
	 */
	public function getConnectors(): array
	{
		if (!$this->connectors)
		{
			foreach ($this->getConnectorsByClassCached() as $name => $value)
			{
				if (!is_string($value)) continue;
				
				$value = \Squid::skeleton($value);
				$this->connectorsByClass[$name] = $value;
			}
			
			$this->connectors = array_values($this->connectorsByClass);
		}
		
		return $this->connectors;
	}
	
	public function sortObjectsByGroups(array $objects): array
	{
		$byGroup = [];
		
		foreach ($objects as $object)
		{
			$class = get_class($object);
			
			if (!isset($byGroup[$class]))
			{
				$byGroup[$class] = [$object];
			}
			else
			{
				$byGroup[$class][] = $object;
			}
		}
		
		return $byGroup;
	}
	
	public function sortExpressionsByGroups(array $whereExpression): array
	{
		$byGroup = [];
		
		foreach ($this->getByFieldRules() as $field => $rule)
		{
			if (!isset($whereExpression[$field]))
				continue;
			
			$value = $whereExpression[$field];
			
			if (is_array($rule))
			{
				$groups = $this->resolveArrayRuleForField($rule, $field, $value);
			}
			else if (is_callable($rule))
			{
				$groups = $this->resolveCallableRuleForField($rule, $field, $value);
			}
			else
			{
				throw new SquidUsageException('Unexpected rule type. Rule must be array or callable');
			}
			
			foreach ($groups as $group => $fieldValues)
			{
				$expressions = $whereExpression;
				$expressions[$field] = $fieldValues;
				$byGroup[$group] = $expressions;
			}
			
			return $byGroup;
		}
		
		if (!$byGroup)
		{
			$all = $this->getConnectorsByClassCached();
			
			$byGroup = array_combine(
				array_keys($all),
				array_pad([], count($all), $whereExpression)
			);
		}
		
		return $byGroup;
	}
	
	public function expressionsIterator(array $fields): iterable
	{
		$sorted = $this->sortExpressionsByGroups($fields);
		
		foreach ($sorted as $group => $expression)
		{
			yield $group => $expression;
		}
	}
	
	public function objectsIterator(array $objects): iterable
	{
		$sorted = $this->sortObjectsByGroups($objects);
		
		foreach ($sorted as $group => $obj)
		{
			yield $group => $obj;
		}
	}
}