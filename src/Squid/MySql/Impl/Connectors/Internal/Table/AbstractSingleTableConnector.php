<?php
namespace Squid\MySql\Impl\Connectors\Internal\Table;


use Squid\MySql\IMySqlConnector;
use Squid\MySql\Connectors\IConnector;
use Squid\MySql\Connectors\Table\ISingleTableConnector;
use Squid\MySql\Connectors\Table\ITableNameConnector;
use Squid\MySql\Impl\Connectors\Internal\Connector;
use Squid\MySql\Impl\Connectors\Table\TableNameConnector;


abstract class AbstractSingleTableConnector extends Connector implements ISingleTableConnector
{
	/** @var ITableNameConnector */
	private $table;
	
	
	public function __construct(AbstractSingleTableConnector $connector = null)
	{
		parent::__construct($connector);
		
		if ($connector)
		{
			$this->table = $connector->getTable();
		}
	}
	
	
	public function getTable(): ITableNameConnector
	{
		return $this->table;
	}
	
	public function getTableName(): string
	{
		return $this->table->name();
	}


	/**
	 * @param IMySqlConnector $connector
	 * @return IConnector
	 */
	public function setConnector(IMySqlConnector $connector): IConnector
	{
		parent::setConnector($connector);
		
		if ($this->table instanceof TableNameConnector)
		{
			$this->table->setConnector($connector);
		}
			
		return $this;
	}

	/**
	 * @param string|ITableNameConnector $table
	 * @return ISingleTableConnector|static
	 */
	public function setTable($table): ISingleTableConnector
	{
		$this->table = new TableNameConnector($table);
		$connector = $this->getConnector(); 
			
		if ($connector)
		{
			$this->table->setConnector($connector);
		}
		
		return $this;
	}
}