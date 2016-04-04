<?php
namespace Squid\MySql\Utils;


use Squid\MySql\Utils\IMappedObject;
use Squid\MySql\Command\ICmdUpsert;


interface IUpsertObject {
	
	/**
	 * @param ICmdUpsert $upsert
	 * @param IMappedObject $object
	 * @return bool
	 */
	public function upsert(ICmdUpsert $upsert, IMappedObject $object);
	
	/**
	 * @param ICmdUpsert $upsert
	 * @param array $objects Array of IMappedObject
	 * @return bool
	 */
	public function upsertAll(ICmdUpsert $upsert, array $objects);
}