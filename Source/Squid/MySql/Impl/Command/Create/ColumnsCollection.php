<?php
namespace Squid\MySql\Impl\Command\Create;


use Squid\MySql\Command\Create\ITablePart;


class ColumnsCollection implements IColumnsTarget 
{
	/** @var ITablePart[] */
	private $columns = [];
	
	
	/**
	 * @param ITablePart $column
	 */
	public function add(ITablePart $column)
	{
		$this->columns[] = $column;
	}

	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		return !((bool)$this->columns);
	}
	
	/**
	 * @return array
	 */
	public function assemble()
	{
		$generatedColumns = [];
		
		foreach ($this->columns as $column) 
		{
			$generatedColumns[] = $column->assemble();
		}
		
		return $generatedColumns;
	}
}