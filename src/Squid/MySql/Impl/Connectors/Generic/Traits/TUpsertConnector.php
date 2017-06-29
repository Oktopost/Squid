<?php
namespace Squid\MySql\Impl\Connectors\Generic\Traits;


use Squid\MySql\Connectors\Generic\IUpsertConnector;
use Squid\MySql\Impl\Connectors\Generic\UpsertConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


/**
 * @mixin IUpsertConnector
 * @mixin AbstractSingleTableConnector
 */
trait TUpsertConnector
{
	/** @var UpsertConnector|null */
	private $_upsertConnector = null;
	
	
	private function getUpsertConnector(): IUpsertConnector
	{
		if (!$this->_upsertConnector)
			$this->_upsertConnector = new UpsertConnector($this);
		
		return $this->_upsertConnector;
	}
	
	
	/**
	 * @param array $row
	 * @param string[]|string $keys
	 * @return int|false
	 */
	public function upsertByKeys(array $row, $keys)
	{
		// TODO
	}
	
	/**
	 * @param array $rows
	 * @param string[]|string $keys
	 * @return int|false
	 */
	public function upsertAllByKeys(array $rows, $keys)
	{
		// TODO
	}
	
	/**
	 * @param array $row
	 * @param string[]|string $valueFields
	 * @return int|false
	 */
	public function upsertByValues(array $row, $valueFields)
	{
		// TODO
	}

	/**
	 * @param array $rows
	 * @param string[]|string $valueFields
	 * @return int|false
	 */
	public function upsertAllByValues(array $rows, $valueFields)
	{
		// TODO
	}
}