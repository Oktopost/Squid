<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\Generic\ICountConnector;
use Squid\MySql\Connectors\Generic\TCountConnector;
use Squid\MySql\Connectors\IGenericConnector;
use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Impl\Connectors\TGenericConnector;
use Squid\MySql\Impl\Connectors\TSingleTableConnector;


class CountConnector implements ICountConnector, IGenericConnector, ISingleTableConnector 
{
	use TCountConnector;
	use TGenericConnector;
	use TSingleTableConnector;
	
	
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