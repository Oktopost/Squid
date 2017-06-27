<?php
namespace Squid\MySql\Impl\Connectors\Extensions\JoinIdentity\Utils;


interface IJoinedDataLoader
{
	/**
	 * @param $object
	 * @return bool
	 */
	public function loadData($object);
}