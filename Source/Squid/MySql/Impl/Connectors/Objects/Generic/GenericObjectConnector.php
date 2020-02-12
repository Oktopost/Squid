<?php
namespace Squid\MySql\Impl\Connectors\Objects\Generic;



use Squid\MySql\Connectors\Objects\IQueryConnector;
use Squid\MySql\Connectors\Objects\Query\ICmdObjectSelect;
use Squid\MySql\Connectors\Objects\Generic\IGenericObjectConnector;

use Squid\MySql\Impl\Connectors\Objects\Query\CmdObjectSelect;
use Squid\MySql\Impl\Connectors\Objects\Plain\TPlainDecorator;
use Squid\MySql\Impl\Connectors\Generic\Traits;
use Squid\MySql\Impl\Connectors\Internal\Objects\AbstractORMConnector;


class GenericObjectConnector extends AbstractORMConnector implements 
	IGenericObjectConnector,
	IQueryConnector
{
	use TPlainDecorator;
	use Traits\TCountConnector;
	use Traits\TDeleteConnector;
	use Traits\TUpdateConnector;
	use Traits\TUpsertConnector;
	use Traits\TSelectConnector;
	use Traits\TInsertConnector;
	
	
	/**
	 * @return ICmdObjectSelect
	 */
	public function query(): ICmdObjectSelect
	{
		$query = new CmdObjectSelect($this->getObjectMap());
		$query
			->setConnector($this->getConnector())
			->from($this->getTableName());
		
		return $query;
	}
}