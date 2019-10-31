<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\Generic\IUpdateConnector;
use Squid\MySql\Connectors\Generic\TUpdateHelper;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class UpdateConnector extends AbstractSingleTableConnector implements IUpdateConnector
{
	use TUpdateHelper;
	
	
	/**
	 * @param array $where
	 * @param array $row
	 * @return int|false
	 */
	public function updateByFields(array $where, array $row)
	{
		return $this->getTable()
			->update()
			->byFields($where)
			->set($row)
			->executeDml(true);
	}
}