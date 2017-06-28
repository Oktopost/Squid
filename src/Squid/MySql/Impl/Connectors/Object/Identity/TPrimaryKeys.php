<?php
namespace Squid\MySql\Impl\Connectors\Object\Identity;


trait TPrimaryKeys
{
	use TPrimaryKeysConsumer;
	
	
	/** @var array */
	private $_primaryKeys;
	
	
	protected function getPrimaryKeys(): array
	{
		return $this->_primaryKeys;
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