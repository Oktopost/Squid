<?php
namespace Squid\MySql\Impl\Connectors\Internal\Generic;


use Squid\MySql\Connectors\Generic\IUpdateConnector;
use Squid\MySql\Connectors\Generic\TUpdateConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class UpdateConnector extends AbstractSingleTableConnector implements IUpdateConnector
{
	use TUpdateConnector;
	
	
	/**
	 * @param array $where
	 * @param array $row
	 * @return int|false
	 */
	public function where(array $where, array $row)
	{
		return $this->getTable()
			->update()
			->byFields($where)
			->set($row)->executeDml(true);
	}
}