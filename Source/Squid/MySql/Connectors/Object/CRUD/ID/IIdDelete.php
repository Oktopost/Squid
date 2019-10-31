<?php
namespace Squid\MySql\Connectors\Object\CRUD\ID;


interface IIdDelete
{
	/**
	 * @param mixed|array $id
	 * @return int|false
	 */
	public function deleteById($id);
}