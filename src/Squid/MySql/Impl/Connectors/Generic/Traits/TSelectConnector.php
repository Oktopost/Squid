<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use Squid\MySql\Connectors\Generic\ISelectConnector;
use Squid\MySql\Impl\Connectors\Generic\SelectConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


/**
 * @mixin ISelectConnector
 * @mixin AbstractSingleTableConnector
 */
trait TSelectConnector
{
	/** @var SelectConnector|null */
	private $_selectConnector = null;
	
	
	private function getSelectConnector(): ISelectConnector
	{
		if (!$this->_selectConnector)
			$this->_selectConnector = new SelectConnector($this);
		
		return $this->_selectConnector;
	}
	
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return array|null|false
	 */
	public function oneByField(string $field, $value)
	{
		// TODO: 
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return array|null|false
	 */
	public function firstByField(string $field, $value)
	{
		// TODO: 
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return array|false
	 */
	public function allByField(string $field, $value)
	{
		// TODO: 
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @param int $limit
	 * @return array|false
	 */
	public function nByField(string $field, $value, int $limit)
	{
		// TODO: 
	}
	
	/**
	 * @param array $fields
	 * @return array|null|false
	 */
	public function oneByFields(array $fields)
	{
		// TODO: 
	}

	/**
	 * @param array $fields
	 * @return array|null|false
	 */
	public function firstByFields(array $fields)
	{
		// TODO: 
	}

	/**
	 * @param array $fields
	 * @return array|false
	 */
	public function allByFields(array $fields)
	{
		// TODO: 
	}

	/**
	 * @param array $fields
	 * @param int $limit
	 * @return array|false
	 */
	public function nByFields(array $fields, int $limit)
	{
		// TODO: 
	}
	
	/**
	 * @return array|false
	 */
	public function all()
	{
		// TODO: 
	}
}