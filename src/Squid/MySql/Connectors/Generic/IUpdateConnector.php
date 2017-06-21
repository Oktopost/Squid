<?php

namespace Squid\MySql\Connectors\Generic;


interface IUpdateConnector
{
	/**
	 * @param string[] $fields
	 * @param array $row
	 * @return int|false
	 */
	public function updateByFields(array $fields, array $row);
	
	/**
	 * @param array $fields
	 * @param array $row
	 * @return int|false
	 */
	public function update(array $fields, array $row);
}