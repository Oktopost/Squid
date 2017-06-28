<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use Squid\MySql\Connectors\Generic\ICountConnector;
use Squid\MySql\Impl\Connectors\Generic\CountConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


/**
 * @mixin ICountConnector
 * @mixin AbstractSingleTableConnector
 */
trait TCountConnector
{
	/** @var CountConnector|null */
	private $_countConnector = null;
	
	
	private function getCountConnector(): ICountConnector
	{
		if (!$this->_countConnector)
			$this->_countConnector = new CountConnector($this);
		
		return $this->_countConnector;
	}
	
	
	/**
	 * @param array $fields
	 * @return int|false
	 */
	public function byFields($fields)
	{
		return $this->getCountConnector()->countByFields($fields);
	}

	/**
	 * @param array $fields
	 * @return bool
	 */
	public function existsByFields($fields): bool
	{
		return $this->getCountConnector()->existsByFields($fields);
	}
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return int|false
	 */
	public function byField(string $field, $value)
	{
		return $this->getCountConnector()->countByField($field, $value);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return bool
	 */
	public function existsByField(string $field, $value): bool
	{
		return $this->getCountConnector()->existsByField($field, $value);
	}
}