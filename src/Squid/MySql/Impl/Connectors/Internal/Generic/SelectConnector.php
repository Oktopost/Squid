<?php
namespace Squid\MySql\Impl\Connectors\Internal\Generic;


use Squid\MySql\Connectors\Generic\ISelectConnector;
use Squid\MySql\Connectors\Generic\TSelectConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class SelectConnector extends AbstractSingleTableConnector implements ISelectConnector 
{
	use TSelectConnector;
	

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