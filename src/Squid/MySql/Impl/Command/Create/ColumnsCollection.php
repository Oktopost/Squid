<?php
namespace Squid\MySql\Impl\Command\Create;


use Squid\MySql\Command\Create\IColumn;


class ColumnsCollection implements IColumnsTarget 
{
	/** @var IColumn[] */
	private $columns = [];
	
	
	/**
	 * @param IColumn $column
	 */
	public function add(IColumn $column)
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