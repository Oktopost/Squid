<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\Generic\TDeleteHelper;
use Squid\MySql\Connectors\Generic\IDeleteConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class DeleteConnector extends AbstractSingleTableConnector implements IDeleteConnector
{
	use TDeleteHelper;
	
	
	/**
	 * @param array $fields
	 * @param int|null $limit
	 * @return int|false
	 */
	public function deleteByFields(array $fields, ?int $limit = null)
	{
		$delete = $this->getTable()->delete()->byFields($fields);
		
		if (!is_null($limit))
		{
			$delete->limitBy($limit);
		}
		
		return $delete->executeDml(true);
	}
}