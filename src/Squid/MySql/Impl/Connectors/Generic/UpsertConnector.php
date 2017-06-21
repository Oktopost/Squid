<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use Squid\MySql\Connectors\IConnector;
use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Connectors\Generic\IUpsertConnector;
use Squid\MySql\Connectors\Generic\TUpsertConnector;
use Squid\MySql\Impl\Connectors\Connector;
use Squid\MySql\Impl\Connectors\TSingleTableConnector;


class UpsertConnector implements IUpsertConnector, IConnector, ISingleTableConnector
{
	use TUpsertConnector;
	use Connector;
	use TSingleTableConnector;


	/**
	 * @param array $rows
	 * @param string[]|string $keys
	 * @return int|false
	 */
	public function allByKeys(array $rows, $keys)
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
	public function allByValues(array $rows, $valueFields)
	{
		return $this->getTable()
			->upsert()
			->values($rows)
			->setUseNewValues($valueFields)
			->executeDml(true);
	}
}