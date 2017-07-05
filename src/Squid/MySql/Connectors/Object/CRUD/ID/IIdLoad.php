<?php
namespace Squid\MySql\Connectors\Object\CRUD\ID;


interface IIdLoad
{
	/**
	 * @param string|array $id
	 * @return mixed|array|null|false
	 */
	public function loadById($id);
}