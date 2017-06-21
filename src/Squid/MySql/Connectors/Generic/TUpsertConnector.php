<?php
namespace Squid\MySql\Connectors\Generic;


/**
 * @mixin IUpsertConnector
 */
trait TUpsertConnector 
{
	/**
	 * @param array $row
	 * @param string[]|string $keys
	 * @return int|false
	 */
	public function byKeys(array $row, $keys)
	{
		return $this->allByKeys([$row], $keys);
	}

	/**
	 * @param array $row
	 * @param string[]|string $valueFields
	 * @return int|false
	 */
	public function byValues(array $row, $valueFields)
	{
		return $this->allByValues([$row], $valueFields);
	}
}