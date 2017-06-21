<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\IGenericConnector;
use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Connectors\Generic\TDeleteConnector;
use Squid\MySql\Connectors\Generic\IDeleteConnector;
use Squid\MySql\Impl\Connectors\TGenericConnector;
use Squid\MySql\Impl\Connectors\TSingleTableConnector;


class DeleteConnector implements IDeleteConnector, IGenericConnector, ISingleTableConnector
{
	use TDeleteConnector;
	use TGenericConnector;
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