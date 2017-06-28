<?php
namespace Squid\MySql\Connectors\Generic;


/**
 * @mixin IUpsertConnector
 */
trait TUpsertHelper 
{
	/**
	 * @param array $row
	 * @param string[]|string $keys
	 * @return int|false
	 */
	public function upsertByKeys(array $row, $keys)
	{
		return $this->upsertAllByKeys([$row], $keys);
	}

	/**
	 * @param array $row
	 * @param string[]|string $valueFields
	 * @return int|false
	 */
	public function upsertByValues(array $row, $valueFields)
	{
		return $this->upsertAllByValues([$row], $valueFields);
	}
}