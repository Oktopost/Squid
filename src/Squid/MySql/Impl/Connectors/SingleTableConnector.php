<?php
namespace Squid\MySql\Impl\Connectors;


use Squid\MySql\Connectors\ISingleTableConnector;
use Squid\MySql\Connectors\Table\ITableNameConnector;
use Squid\MySql\Impl\Connectors\Table\TableNameConnector;


class SingleTableConnector extends Connector implements ISingleTableConnector
{
	/** @var ITableNameConnector */
	private $table;
	
	
	public function getTable(): ITableNameConnector
	{
		return $this->getTable();
	}
	
	public function getTableName(): string
	{
		return $this->getTable()->name();
	}
	
	
	public function __construct(ISingleTableConnector $connector = null)
	{
		parent::__construct($connector);
		
		if ($connector)
		{
			$this->table = $connector->getTable();
		}
	}


	/**
	 * @param string|ITableNameConnector $table
	 * @return ISingleTableConnector|static
	 */
	public function setTable($table): ISingleTableConnector
	{
		$this->table = new TableNameConnector($table);
		return $this;
	}
}