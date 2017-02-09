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
	 * @return string
	 */
	public function generate()
	{
		$generatedColumns = [];
		
		foreach ($this->columns as $column) 
		{
			$generatedColumns[] = $column->generate();
		}
		
		return implode(",\n", $generatedColumns);
	}
}