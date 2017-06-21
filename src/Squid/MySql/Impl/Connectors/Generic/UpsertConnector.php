<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\IGenericConnector;
use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Connectors\Generic\IUpsertConnector;
use Squid\MySql\Connectors\Generic\TUpsertConnector;
use Squid\MySql\Impl\Connectors\TGenericConnector;
use Squid\MySql\Impl\Connectors\TSingleTableConnector;


class UpsertConnector implements IUpsertConnector, IGenericConnector, ISingleTableConnector
{
	use TUpsertConnector;
	use TGenericConnector;
	use TSingleTableConnector;


	/**
	 * @param array $rows
	 * @param string[]|string $keys
	 * @return int|false
	 */
	public function upsertAllByKeys(array $rows, $keys)
	{
		return $this->getTable()
			->upsert()
			->values($rows)
			->setDuplicateKeys($keys)
			->executeDml(true);
	}

	/**
	 * @param array $rows
	 * @param string[]|string $valueFields
	 * @return int|false
	 */
	public function upsertAllByValues(array $rows, $valueFields)
	{
		return $this->getTable()
			->upsert()
			->values($rows)
			->setUseNewValues($valueFields)
			->executeDml(true);
	}
}