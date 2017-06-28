<?php
namespace Squid\MySql\Connectors\Object\CRUD\ID;


interface IIDLoad
{
	/**
	 * @param string|array $id
	 * @return mixed|null|false
	 */
	public function loadById($id);
}