<?php
namespace Squid\MySql\Connectors\Generic;


/**
 * @mixin ICountConnector
 */
trait TCountHelper
{
	/**
	 * @param string $field
	 * @param mixed $value
	 * @return int|false
	 */
	public function byField(string $field, $value)
	{
		return $this->byFields([$field => $value]);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return bool
	 */
	public function existsByField(string $field, $value): bool
	{
		return $this->existsByFields([$field => $value]);
	}
}