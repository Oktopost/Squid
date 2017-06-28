<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\Generic\TCountHelper;
use Squid\MySql\Connectors\Generic\ICountConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class CountConnector extends AbstractSingleTableConnector implements ICountConnector
{
	use TCountHelper;
	
	
	/**
	 * @param array $fields
	 * @return int|false
	 */
	public function byFields($fields)
	{
		return $this->getTable()->select()->byFields($fields)->queryCount();
	}

	/**
	 * @param array $fields
	 * @return bool
	 */
	public function existsByFields($fields): bool
	{
		return $this->getTable()->select()->byFields($fields)->queryExists();
	}
}