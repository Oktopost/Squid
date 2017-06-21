<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\IConnector;
use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Connectors\Generic\ISelectConnector;
use Squid\MySql\Connectors\Generic\TSelectConnector;
use Squid\MySql\Impl\Connectors\Connector;
use Squid\MySql\Impl\Connectors\TSingleTableConnector;


class SelectConnector implements ISelectConnector, IConnector, ISingleTableConnector 
{
	use TSelectConnector;
	use Connector;
	use TSingleTableConnector;
	

	/**
	 * @param array $fields
	 * @return array|false
	 */
	public function allByFields(array $fields)
	{
		return $this->getTable()->select()->byFields($fields)->query();
	}

	/**
	 * @param array $fields
	 * @param int $limit
	 * @return array|false
	 */
	public function nByFields(array $fields, int $limit)
	{
		return $this->getTable()->select()->byFields($fields)->limitBy($limit)->query();
	}

	/**
	 * @return array|false
	 */
	public function all()
	{
		return $this->getTable()->select()->query();
	}
}