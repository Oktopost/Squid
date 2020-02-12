<?php
namespace Squid\MySql\Connectors\Objects\CRUD\ID;


interface IIdLoad
{
	/**
	 * @param string|array $id
	 * @return mixed|array|null|false
	 */
	public function loadById($id);
}