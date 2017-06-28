<?php
namespace Squid\MySql\Impl\Connectors\Object\Primary;


use Squid\Exceptions\SquidException;


trait TIdKey
{
	use TIdKeyConsumer;
	
	
	/** @var array */
	private $_idKey;
	private $_idField;
	private $_idProperty;
	
	
	protected function getIdKey(): array
	{
		return $this->_idKey;
	}

	
	private function getIdField(): string
	{
		return $this->_idField;
	}
	
	private function getIdProperty(): string
	{
		return $this->_idProperty;
	}
	

	/**
	 * @param array|string $column Column name to property name
	 * @param null|string $property
	 * @return static
	 * @throws SquidException
	 */
	public function setIdKey($column, ?string $property = null)
	{
		if (is_string($column))
		{
			$this->_idField = $column;
			$this->_idProperty = ($property ?: $column);
			$this->_idKey = [$this->_idField => $this->_idProperty];
		}
		else if (count($column) > 1)
		{
			throw new SquidException('IDConnector can have only one primary key!');
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