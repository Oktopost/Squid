<?php
namespace Squid\MySql\Connectors\Generic;


interface ITableColumnsConnector
{
	/**
	 * @param array $columns
	 * @return static
	 */
	public function setColumns(...$columns);
	
	/**
	 * @return array
	 */
	public function getColumns();
}