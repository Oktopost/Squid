<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\IGenericConnector;
use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Connectors\Generic\TInsertConnector;
use Squid\MySql\Connectors\Generic\IInsertConnector;
use Squid\MySql\Impl\Connectors\TGenericConnector;
use Squid\MySql\Impl\Connectors\TSingleTableConnector;


class InsertConnector implements IInsertConnector, IGenericConnector, ISingleTableConnector 
{
	use TInsertConnector;
	use TGenericConnector;
	use TSingleTableConnector;
	
	
	private function doInsert(array $data, bool $ignore, ?array $fields = null)
	{
		return $this->getConnector()
			->insert()
			->into($this->getTableName(), $fields)
			->ignore($ignore)
			->valuesBulk($data)
			->executeDml(true);
	}
	
	
	/**
	 * @param array $rows
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insertAll(array $rows, bool $ignore = false)
	{
		return $this->doInsert($rows, $ignore, null);
	}
	
	/**
	 * @param array $fields
	 * @param array $rows
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function insertAllIntoFields(array $fields, array $rows, bool $ignore = false)
	{
		return $this->doInsert($rows, $ignore, $fields);
	}
}