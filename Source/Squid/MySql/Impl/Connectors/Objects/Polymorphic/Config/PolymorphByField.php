<?php
namespace Squid\MySql\Impl\Connectors\Objects\Polymorphic\Config;


use Squid\MySql\Connectors\Objects\Generic\IGenericObjectConnector;

use Squid\Exceptions\SquidUsageException;


class PolymorphByField extends AbstractPolymorphByField
{
	private $byField = [];
	private $ignoredFields = [];
	
	/** @var IGenericObjectConnector[] */
	private $byClass = [];
	
	
	/**
	 * @return array
	 */
	protected function getByFieldRules(): array
	{
		return $this->byField;
	}
	
	/**
	 * @return IGenericObjectConnector[]
	 */
	protected function getConnectorsByClass(): array
	{
		return $this->byClass;
	}

	/**
	 * @return string[]
	 */
	protected function getIgnoredFields(): array
	{
		return $this->ignoredFields;
	}


	/**
	 * @param string|array $className If array, second parameter is omitted.
	 * @param string|IGenericObjectConnector|null $connector
	 * @return PolymorphByField|static
	 */
	public function addClass($className, $connector = null): PolymorphByField
	{
		if (is_array($className))
		{
			$this->byClass = ($this->byClass ? 
				array_merge($this->byClass, $className) : 
				$className);
		}
		else if (is_null($connector))
		{
			throw new SquidUsageException('Connector must be set if $className is not an array');
		}
		else
		{
			$this->byClass[$className] = $connector;
		}
		
		return $this;
	}

	/**
	 * @param string|string[] $fields
	 * @return PolymorphByField
	 */
	public function setIgnoreFields($fields): PolymorphByField
	{
		if (is_array($fields))
		{
			$this->ignoredFields = array_merge($this->ignoredFields, $fields);
		}
		else
		{
			$this->ignoredFields[] = $fields;
		}
		
		return $this;
	}

	/**
	 * @param array $classes
	 * @return PolymorphByField|static
	 */
	public function addClasses(array $classes): PolymorphByField
	{
		return $this->addClass($classes);
	}

	/**
	 * @param string|array $fieldName
	 * @param callable|array|null $rule
	 * @return PolymorphByField|static
	 */
	public function addFieldRule($fieldName, $rule = null): PolymorphByField
	{
		if (is_array($fieldName))
		{
			$this->byField = ($this->byField ? 
				array_merge($this->byField, $fieldName) : 
				$fieldName);
		}
		else if (is_null($rule))
		{
			throw new SquidUsageException('Rule must be set if $fieldName is not an array');
		}
		else
		{
			$this->byField[$fieldName] = $rule;
		}
		
		return $this;
	}
}