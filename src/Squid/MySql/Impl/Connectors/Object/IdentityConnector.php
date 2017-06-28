<?php
namespace Squid\MySql\Impl\Connectors\Object;


use Squid\MySql\Connectors\Object\IIdentityConnector;
use Squid\MySql\Impl\Connectors\Object\Identity\TPrimaryKeys;
use Squid\MySql\Impl\Connectors\Internal\Object\AbstractORMConnector;


class IdentityConnector extends AbstractORMConnector implements IIdentityConnector
{
	use TPrimaryKeys;
	
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object)
	{
		// TODO: Implement delete() method.
	}

	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object)
	{
		// TODO: Implement update() method.
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object)
	{
		// TODO: Implement upsert() method.
	}
}