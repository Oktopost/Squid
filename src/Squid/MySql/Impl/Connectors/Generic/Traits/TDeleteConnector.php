<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use Squid\MySql\Connectors\Generic\IDeleteConnector;
use Squid\MySql\Impl\Connectors\Generic\DeleteConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


/**
 * @mixin IDeleteConnector
 * @mixin AbstractSingleTableConnector
 */
trait TDeleteConnector
{
	/** @var DeleteConnector|null */
	private $_deleteConnector = null;
	
	
	private function getDeleteConnector(): IDeleteConnector
	{
		if (!$this->_deleteConnector)
			$this->_deleteConnector = new DeleteConnector($this);
		
		return $this->_deleteConnector;
	}
	
	
	/**
	 * @param string $field
	 * @param mixed $value
	 * @param int|null $limit
	 * @return int|false
	 */
	public function deleteByField(string $field, $value, ?int $limit = null)
	{
		// TODO: 
	}

	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return int|false
	 */
	public function deleteByFields(array $fields, ?int $limit = null)
	{
		// TODO: 
	}
}