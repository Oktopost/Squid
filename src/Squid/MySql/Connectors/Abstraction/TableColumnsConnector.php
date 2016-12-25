<?php
namespace Squid\MySql\Connectors\Abstraction;


use Squid\MySql\Connectors\Generic\ITableColumnsConnector;
use Squid\Exceptions\SquidException;


class TableColumnsConnector implements ITableColumnsConnector
{
	private $table = false;
	private $columns = [];
	
	
	/**
	 * @param array $columns
	 * @return static
	 */
	public function setColumns(...$columns)
	{
		if (is_string($columns[0])) 
		{
			$this->columns = $columns;
		}
		else if (is_array($columns[0]) && count($columns) == 1)
		{
			$this->columns = $columns[0];
		}
		else
		{
			throw new SquidException('Passed columns must be strings, or one array of strings');
		}
		
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getColumns()
	{
		if ($this->table)
			throw new SquidException('Table must be set');
		
		return $this->columns;
	}
}