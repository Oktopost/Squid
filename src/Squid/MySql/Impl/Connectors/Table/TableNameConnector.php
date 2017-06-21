<?php
namespace Squid\MySql\Impl\Connectors\Table;


use Squid\MySql\Command;
use Squid\MySql\IMySqlConnector;
use Squid\MySql\Connectors\Table\ITableNameConnector;


class TableNameConnector implements ITableNameConnector
{
	private $name;
	
	/** @var IMySqlConnector */
	private $connector;
	
	
	/**
	 * @param string|ITableNameConnector $tableName
	 */
	public function __construct($tableName)
	{
		if ($tableName instanceof ITableNameConnector)
			$tableName = $tableName->name();
		
		$this->name = $tableName;
	}
	
	
	public function setConnector(IMySqlConnector $connector): TableNameConnector
	{
		$this->connector = $connector;
		return $this;
	}
	

	public function select(?string $alias = null): Command\ICmdSelect
	{
		return $this->connector->select()->from($this->name, $alias ?: false);
	}

	public function update(): Command\ICmdUpdate
	{
		return $this->connector->update()->table($this->name);
	}

	public function insert(): Command\ICmdInsert
	{
		return $this->connector->insert()->into($this->name);
	}

	public function delete(): Command\ICmdDelete
	{
		return $this->connector->delete()->from($this->name);
	}

	public function upsert(): Command\ICmdUpsert
	{
		return $this->connector->upsert()->into($this->name);
	}
	
	public function name(): string
	{
		return $this->name;
	}
}