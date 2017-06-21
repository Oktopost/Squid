<?php
namespace Squid\MySql\Connectors\Generic;


/**
 * @mixin IInsertConnector
 */
trait TInsertConnector
{
	/**
	 * @param array $row
	 * @param bool $ignore
	 * @return int|null Number of affected rows
	 */
	public function row(array $row, bool $ignore = false): ?int
	{
		return $this->all([$row], $ignore);
	}
}