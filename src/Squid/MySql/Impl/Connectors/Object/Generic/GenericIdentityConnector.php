<?php
namespace Squid\MySql\Impl\Connectors\Object\Generic;


use Squid\MySql\Connectors\Object\Generic\IGenericIdentityConnector;


class GenericIdentityConnector extends GenericObjectConnector implements 
	IGenericIdentityConnector
{

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object)
	{
		// TODO: Implement delete() method.
	}

	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object)
	{
		// TODO: Implement upsert() method.
	}

	/**
	 * @param mixed $object
	 * @param string[] $byFields
	 * @return false|int
	 */
	public function updateByFields($object, array $byFields)
	{
		// TODO: Implement updateByFields() method.
	}
}