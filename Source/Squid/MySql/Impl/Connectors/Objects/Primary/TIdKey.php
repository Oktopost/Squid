<?php
namespace Squid\MySql\Impl\Connectors\Objects\Primary;


use Squid\Exceptions\SquidException;


trait TIdKey
{
	/** @var array */
	private $_idKey;
	
	private $_idField;
	private $_idProperty;

	
	private function getIdField(): string
	{
		return $this->_idField;
	}
	
	private function getIdProperty(): string
	{
		return $this->_idProperty;
	}
	
	
	protected function getIdKey(): array
	{
		return $this->_idKey;
	}
	

	/**
	 * @param array|string $column Column name to property name
	 * @param null|string $property
	 * @return static
	 * @throws SquidException
	 */
	public function setIdKey($column, ?string $property = null)
	{
		if ($this->_idField)
		{
			throw new SquidException('setIdKey can only be called once');
		}
		else if (is_string($column))
		{
			$this->_idField = $column;
			$this->_idProperty = ($property ?: $column);
			$this->_idKey = [$this->_idField => $this->_idProperty];
		}
		else if (count($column) > 1)
		{
			throw new SquidException('Only one primary key allowed for this connector');
		}
		else	
		{
			$this->_idField = key($column);
			$this->_idProperty = $column[$this->_idField];
			$this->_idKey = $column;
		}
		
		return $this;
	}
}