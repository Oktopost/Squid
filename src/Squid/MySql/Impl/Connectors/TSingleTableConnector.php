<?php
namespace Squid\MySql\Impl\Connectors;


use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Connectors\Table\ITableNameConnector;
use Squid\MySql\Impl\Connectors\Table\TableNameConnector;


/**
 * @mixin ISingleTableConnector
 */
trait TSingleTableConnector
{
	/** @var ITableNameConnector */
	private $_table;
	
	
	public function getTable(): ITableNameConnector
	{
		return $this->getTable();
	}
	
	public function getTableName(): string
	{
		return $this->getTable()->name();
	}
	
	
	/**
	 * @param string|ITableNameConnector $table
	 * @return ISingleTableConnector|static
	 */
	public function setTable($table): ISingleTableConnector
	{
		$this->_table = new TableNameConnector($table);
		return $this;
	}
}