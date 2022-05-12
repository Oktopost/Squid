<?php
namespace Squid\MySql\Connectors\Generic;


interface IUpdateConnector
{
	/**
	 * @param string[] $fields
	 * @param array $row
	 * @return int|false
	 */
	public function updateByRowFields(array $fields, array $row);
	
	/**
	 * @param array $where
	 * @param array $row
	 * @return int|false
	 */
	public function updateByFields(array $where, array $row);
}