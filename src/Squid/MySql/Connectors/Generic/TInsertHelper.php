<?php
namespace Squid\MySql\Connectors\Generic;


/**
 * @mixin IInsertConnector
 */
trait TInsertHelper
{
	/**
	 * @param array $row
	 * @param bool $ignore
	 * @return int|null Number of affected rows
	 */
	public function insertRow(array $row, bool $ignore = false): ?int
	{
		return $this->insertAll([$row], $ignore);
	}
}