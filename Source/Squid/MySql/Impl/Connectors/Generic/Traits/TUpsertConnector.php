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
	 * @param string[]|string $keys
	 * @param array $row
	 * @return int|false
	 */
	public function upsertByKeys($keys, array $row)
	{
		return $this->getUpsertConnector()->upsertByKeys($keys, $row);
	}
	
	/**
	 * @param string[]|string $keys
	 * @param array $rows
	 * @return int|false
	 */
	public function upsertAllByKeys($keys, array $rows)
	{
		return $this->getUpsertConnector()->upsertAllByKeys($keys, $rows);
	}
	
	/**
	 * @param string[]|string $valueFields
	 * @param array $row
	 * @return int|false
	 */
	public function upsertByValues($valueFields, array $row)
	{
		return $this->getUpsertConnector()->upsertByValues($valueFields, $row);
	}

	/**
	 * @param string[]|string $valueFields
	 * @param array $rows
	 * @return int|false
	 */
	public function upsertAllByValues($valueFields, array $rows)
	{
		return $this->getUpsertConnector()->upsertAllByValues($valueFields, $rows);
	}
}