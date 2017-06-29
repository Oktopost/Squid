<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use Squid\MySql\Connectors\Generic\IInsertConnector;
use Squid\MySql\Impl\Connectors\Generic\InsertConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


/**
 * @mixin IInsertConnector
 * @mixin AbstractSingleTableConnector
 */
trait TInsertConnector
{
	/** @var InsertConnector|null */
	private $_insertConnector = null;
	
	
	private function getInsertConnector(): IInsertConnector
	{
		if (!$this->_insertConnector)
			$this->_insertConnector = new InsertConnector($this);
		
		return $this->_insertConnector;
	}
	
	
	/**
	 * @param array $row
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insertRow(array $row, bool $ignore = false)
	{
		// TODO: 
	}

	/**
	 * @param array $rows
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insertAll(array $rows, bool $ignore = false)
	{
		// TODO: 
	}

	/**
	 * @param array $fields
	 * @param array $rows
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insertAllIntoFields(array $fields, array $rows, bool $ignore = false)
	{
		// TODO: 
	}
}