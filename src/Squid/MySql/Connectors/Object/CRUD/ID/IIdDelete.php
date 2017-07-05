<?php
namespace Squid\MySql\Connectors\Object\CRUD\ID;


interface IIdDelete
{
	/**
	 * @param string|array $id
	 * @return int|false
	 */
	public function deleteById($id);
}