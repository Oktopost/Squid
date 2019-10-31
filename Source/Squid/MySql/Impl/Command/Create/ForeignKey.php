<?php
namespace Squid\MySql\Impl\Command\Create;


use Squid\MySql\ForeignKeyBehavior;
use Squid\MySql\Command\Create\IForeignKey;


class ForeignKey implements IForeignKey
{
	private $name = '';
	
	private $column		= null;
	private $onTable	= null;
	private $onColumn   = null;
	private $onUpdate   = null;
	private $onDelete   = null;
	
	
	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * @return string
	 */
	public function getTargetTable()
	{
		return $this->onTable;
	}
	
	/**
	 * @return string
	 */
	public function getTargetColumn()
	{
		return $this->onColumn;
	}
	
	/**
	 * @return string
	 */
	public function getSourceColumn()
	{
		return $this->column;
	}
	
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function name($name)
	{
		$this->name = $name;
		return $this;
	}
	
	/**
	 * @param string $column
	 * @return static
	 */
	public function column($column)
	{
		$this->column = $column;
		return $this;
	}
	
	/**
	 * @param string $table
	 * @param string $column
	 * @return static
	 */
	public function on($table, $column)
	{
		$this->onTable = $table;
		$this->onColumn = $column;
		return $this;
	}
	
	/**
	 * @param string $behavior
	 * @return static
	 */
	public function onUpdate($behavior)
	{
		$this->onUpdate = $behavior;
		return $this;
	}
	
	/**
	 * @return static
	 */
	public function onUpdateCascade()
	{
		return $this->onUpdate(ForeignKeyBehavior::CASCADE);
	}
	
	/**
	 * @return static
	 */
	public function onUpdateRestrict()
	{
		return $this->onUpdate(ForeignKeyBehavior::RESTRICT);
	}
	
	/**
	 * @return static
	 */
	public function onUpdateSetNull()
	{
		return $this->onUpdate(ForeignKeyBehavior::SET_NULL);
	}
	
	/**
	 * @param string $behavior
	 * @return static
	 */
	public function onDelete($behavior)
	{
		$this->onDelete = $behavior;
		return $this;
	}
	
	/**
	 * @return static
	 */
	public function onDeleteCascade()
	{
		return $this->onDelete(ForeignKeyBehavior::CASCADE);
	}
	
	/**
	 * @return static
	 */
	public function onDeleteRestrict()
	{
		return $this->onDelete(ForeignKeyBehavior::RESTRICT);
	}
	
	/**
	 * @return static
	 */
	public function onDeleteSetNull()
	{
		return $this->onDelete(ForeignKeyBehavior::SET_NULL);
	}
	
	/**
	 * @return string
	 */
	public function assemble()
	{
		$command = 
			"CONSTRAINT `$this->name` " . 
			"FOREIGN KEY (`$this->column`) " . 
			"REFERENCES `$this->onTable`(`$this->onColumn`)";
		
		if ($this->onUpdate)
			$command .= ' ON UPDATE ' . ForeignKeyBehavior::get($this->onUpdate);
		
		if ($this->onDelete)
			$command .= ' ON DELETE ' . ForeignKeyBehavior::get($this->onDelete);
		
		return $command;
	}
}