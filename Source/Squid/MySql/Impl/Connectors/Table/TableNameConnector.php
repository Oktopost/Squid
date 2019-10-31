<?php
namespace Squid\MySql\Impl\Connectors\Table;


use Squid\MySql\Command;
use Squid\MySql\Impl\Connectors\Internal\Connector;
use Squid\MySql\Connectors\Table\ITableNameConnector;


class TableNameConnector extends Connector implements ITableNameConnector
{
	private $name;
	
	
	/**
	 * @param string $tableName
	 */
	public function __construct(string $tableName)
	{
		parent::__construct();
		$this->name = $tableName;
	}
	

	public function select(?string $alias = null): Command\ICmdSelect
	{
		return $this->getConnector()->select()->from($this->name, $alias ?: false);
	}

	public function update(): Command\ICmdUpdate
	{
		return $this->getConnector()->update()->table($this->name);
	}

	public function insert(): Command\ICmdInsert
	{
		return $this->getConnector()->insert()->into($this->name);
	}

	public function delete(): Command\ICmdDelete
	{
		return $this->getConnector()->delete()->from($this->name);
	}

	public function upsert(): Command\ICmdUpsert
	{
		return $this->getConnector()->upsert()->into($this->name);
	}
	
	
	public function name(): string
	{
		return $this->name;
	}
	
	public function __toString()
	{
		return $this->name;
	}
}