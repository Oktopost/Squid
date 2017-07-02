<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\Generic\IUpsertConnector;
use Squid\MySql\Connectors\Generic\TUpsertHelper;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class UpsertConnector extends AbstractSingleTableConnector implements IUpsertConnector
{
	use TUpsertHelper;


	/**
	 * @param string[]|string $keys
	 * @param array $rows
	 * @return int|false
	 */
	public function upsertAllByKeys($keys, array $rows)
	{
		return $this->getTable()
			->upsert()
			->valuesBulk($rows)
			->setDuplicateKeys($keys)
			->executeDml(true);
	}

	/**
	 * @param string[]|string $valueFields
	 * @param array $rows
	 * @return int|false
	 */
	public function upsertAllByValues($valueFields, array $rows)
	{
		return $this->getTable()
			->upsert()
			->valuesBulk($rows)
			->setUseNewValues($valueFields)
			->executeDml(true);
	}
}