<?php
namespace Squid\MySql\Connectors\Extensions\JoinIdentity;


interface IJoinIdentityConfig
{
	/**
	 * @param mixed|array $object
	 * @return mixed|array|null
	 */
	public function getData($object);
	
	/**
	 * @return bool If true, after deleting the object entity, should the data entity also be deleted? If the
	 * deletion is handled by the database itself (for example a foreign key constraint), this should return 
	 * false.
	 */
	public function onDeleteObjectCascadeData(): bool;

	/**
	 * @param mixed|array $object
	 */
	public function beforeDataSave($object);

	/**
	 * @param mixed|array $object
	 * @return mixed|array|null
	 */
	public function getDataIdentifier($object);

	/**
	 * @param mixed $object
	 * @param mixed $data
	 * @return mixed
	 */
	public function combine($object, $data);

	/**
	 * @param array $objects
	 * @param array $data
	 * @return array
	 */
	public function combineAll(array $objects, array $data): array;
}