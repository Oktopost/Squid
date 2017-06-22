<?php
namespace Squid\MySql\Impl\Connectors\Internal\Generic;


use Squid\MySql\Connectors\Generic\TDeleteConnector;
use Squid\MySql\Connectors\Generic\IDeleteConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class DeleteConnector extends AbstractSingleTableConnector implements IDeleteConnector
{
	use TDeleteConnector;
	
	
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