<?php
namespace Squid\MySql\Connectors\Object\CRUD\ID;


interface IIDDelete
{
	/**
	 * @param string|array $id
	 * @return int|false
	 */
	public function deleteById($id);
}