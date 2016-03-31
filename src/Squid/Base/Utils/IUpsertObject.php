<?php
namespace Squid\Base\Utils;


use \Squid\Base\Utils\IMappedObject;
use \Squid\Base\Cmd\ICmdUpsert;


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