<?php
namespace Squid\MySql\Connectors\Generic;


/**
 * @mixin IUpsertConnector
 */
trait TUpsertHelper 
{
	/**
	 * @param string[]|string $keys
	 * @param array $row
	 * @return int|false
	 */
	public function upsertByKeys($keys, array $row)
	{
		return $this->upsertAllByKeys($keys, [$row]);
	}

	/**
	 * @param string[]|string $valueFields
	 * @param array $row
	 * @return int|false
	 */
	public function upsertByValues($valueFields, array $row)
	{
		return $this->upsertAllByValues($valueFields, [$row]);
	}
}