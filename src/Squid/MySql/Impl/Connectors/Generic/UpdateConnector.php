<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\IGenericConnector;
use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Connectors\Generic\IUpdateConnector;
use Squid\MySql\Connectors\Generic\TUpdateConnector;
use Squid\MySql\Impl\Connectors\TGenericConnector;
use Squid\MySql\Impl\Connectors\TSingleTableConnector;


class UpdateConnector implements IUpdateConnector, IGenericConnector, ISingleTableConnector
{
	use TUpdateConnector;
	use TGenericConnector;
	use TSingleTableConnector;
	
	
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