<?php
namespace Squid\MySql\Impl\Connectors\Object\Identity;


trait TPrimaryKeys
{
	use TPrimaryKeysConsumer;
	
	
	private $_primaryKeys;
	
	
	protected function getPrimaryKeys(): array
	{
		return $this->_primaryKeys;
	}
	
	
	/**
	 * @param array|string $keys
	 * @return static
	 */
	public function setPrimaryKeys($keys)
	{
		$this->_primaryKeys = (is_array($keys) ? $keys : [$keys]);
		return $this;
	}
}