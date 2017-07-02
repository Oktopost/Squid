<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use Squid\MySql\Connectors\Generic\IUpdateConnector;
use Squid\MySql\Impl\Connectors\Generic\UpdateConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


/**
 * @mixin IUpdateConnector
 * @mixin AbstractSingleTableConnector
 */
trait TUpdateConnector
{
	/** @var UpdateConnector|null */
	private $_updateConnector = null;
	
	
	private function getUpdateConnector(): IUpdateConnector
	{
		if (!$this->_updateConnector)
			$this->_updateConnector = new UpdateConnector($this);
		
		return $this->_updateConnector;
	}
	
	
	/**
	 * @param string[] $fields
	 * @param array $row
	 * @return int|false
	 */
	public function updateByRowFields(array $fields, array $row)
	{
		return $this->getUpdateConnector()->updateByRowFields($fields, $row);
	}
	
	/**
	 * @param array $where
	 * @param array $row
	 * @return int|false
	 */
	public function updateByFields(array $where, array $row)
	{
		return $this->getUpdateConnector()->updateByFields($where, $row); 
	}
}