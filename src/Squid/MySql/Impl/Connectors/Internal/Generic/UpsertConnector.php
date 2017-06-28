<?php
namespace Squid\MySql\Impl\Connectors\Internal\Generic;


use Squid\MySql\Connectors\Generic\IUpsertConnector;
use Squid\MySql\Connectors\Generic\TUpsertHelper;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class UpsertConnector extends AbstractSingleTableConnector implements IUpsertConnector
{
	use TUpsertHelper;


	/**
	 * @param array $rows
	 * @param string[]|string $keys
	 * @return int|false
	 */
	public function allByKeys(array $rows, $keys)
	{
		return $this->getTable()
			->upsert()
			->valuesBulk($rows)
			->setDuplicateKeys($keys)
			->executeDml(true);
	}

	/**
	 * @param array $rows
	 * @param string[]|string $valueFields
	 * @return int|false
	 */
	public function allByValues(array $rows, $valueFields)
	{
		return $this->getTable()
			->upsert()
			->valuesBulk($rows)
			->setUseNewValues($valueFields)
			->executeDml(true);
	}
}