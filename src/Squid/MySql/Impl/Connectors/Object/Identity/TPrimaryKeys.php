<?php
namespace Squid\MySql\Impl\Connectors\Object\Identity;


trait TPrimaryKeys
{
	use TPrimaryKeysConsumer;
	
	
	/** @var array */
	private $_primaryKeys;
	
	/** @var array */
	private $_primaryFields;
	
	/** @var array */
	private $_primaryProps;
	
	
	protected function getPrimaryKeys(): array
	{
		return $this->_primaryKeys;
	}
	
	protected function getKeysCount(): int
	{
		return count($this->_primaryKeys);
	}
	
	protected function getPrimaryFields(): array 
	{
		if (!$this->_primaryFields)
			$this->_primaryFields = array_keys($this->_primaryKeys);
			
		return $this->_primaryFields;
	}
	
	protected function getPrimaryProperties(): array 
	{
		if (!$this->_primaryProps)
			$this->_primaryProps = array_values($this->_primaryKeys);
			
		return $this->_primaryProps;
	}
	
	
	/**
	 * @param string|array $keys Maps of Columns to Properties
	 * @return static
	 */
	public function setPrimaryKeys($keys)
	{
		if (is_string($keys))
			$keys = [$keys => $keys];
		else if (isset($keys[0]))
			$keys = array_combine($keys, $keys);
		
		$this->_primaryKeys = $keys;
		return $this;
	}
}