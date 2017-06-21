<?php
namespace Squid\MySql\Connectors\Object\CRUD;


interface IObjectSave extends 
	IObjectUpdate,
	IObjectInsert
{
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function save($object);
}