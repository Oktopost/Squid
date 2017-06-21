<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\IConnector;
use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Connectors\Generic\TDeleteConnector;
use Squid\MySql\Connectors\Generic\IDeleteConnector;
use Squid\MySql\Impl\Connectors\Connector;
use Squid\MySql\Impl\Connectors\TSingleTableConnector;


class DeleteConnector implements IDeleteConnector, IConnector, ISingleTableConnector
{
	use TDeleteConnector;
	use Connector;
	use TSingleTableConnector;
	
	
	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return int|false
	 */
	public function byFields(array $fields, ?int $limit = null)
	{
		$delete = $this->getTable()->delete()->byFields($fields);
		
		if (!is_null($limit))
		{
			$delete->limitBy($limit);
		}
		
		return $delete->executeDml(true);
	}
}