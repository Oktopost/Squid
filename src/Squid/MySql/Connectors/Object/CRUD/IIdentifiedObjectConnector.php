<?php
namespace Squid\MySql\Connectors\Object\CRUD;


interface IIdentifiedObjectConnector extends 
	IObjectCRUD,
	IObjectSave
{
	/**
	 * @param mixed|array $id
	 * @return mixed|null|false
	 */
	public function load($id);

	/**
	 * @param mixed $id
	 * @return mixed|false
	 */
	public function deleteById($id);
}