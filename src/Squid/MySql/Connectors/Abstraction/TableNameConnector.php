<?php
namespace Squid\MySql\Connectors\Abstraction;


use Squid\Exceptions\SquidException;
use Squid\MySql\Connectors\Generic\ITableNameConnector;


class TableNameConnector implements ITableNameConnector
{
	private $table = false;
	
	
	/**
	 * @param string $table
	 * @return static
	 */
	public function setTable($table)
	{
		if ($this->table)
			throw new SquidException('Table name already set to ' . $this->table);
		
		$this->table = $table;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getTable()
	{
		if ($this->table)
			throw new SquidException('Table name must be set');
		
		return $this->table;
	}
}