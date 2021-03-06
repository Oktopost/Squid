<?php
namespace Squid\MySql\Connectors\Generic;


/**
 * @mixin IDeleteConnector
 */
trait TDeleteHelper
{
	/**
	 * @param string $field
	 * @param mixed $value
	 * @param int|null $limit
	 * @return int|null
	 */
	public function deleteByField(string $field, $value, ?int $limit = null): ?int
	{
		return $this->deleteByFields([$field => $value], $limit);
	}
}