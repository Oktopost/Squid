<?php
namespace Squid\MySql\Impl;


use Squid\MySql\Impl;
use Squid\MySql\Command;
use Squid\MySql\IMySqlConnector;
use Squid\MySql\Command\IMySqlCommand;
use Squid\MySql\Connection\IMySqlConnection;


class MySqlConnector implements IMySqlConnector 
{
	private $connectionName;
	
	/** @var IMySqlConnection */
	private $connection;
	
	
	/**
	 * @param IMySqlCommand $command
	 * @return IMySqlCommand
	 */
	private function initialize(IMySqlCommand $command)
	{
		$command->setConnection($this->connection);
		return $command;
	}
	
	
	/**
	 * @param string $name
	 */
	public function __construct($name)
	{
		$this->connectionName = $name;
	}
	
	
	/**
	 * @param IMySqlConnection $connection
	 */
	public function setConnection(IMySqlConnection $connection) 
	{
		$this->connection = $connection;
	}
	
	/**
	 * @return Command\ICmdController
	 */
	public function controller() 
	{
		return $this->initialize(new Impl\Command\CmdController());
	}
	
	/**
	 * @return Command\ICmdDelete
	 */
	public function delete() 
	{
		return $this->initialize(new Impl\Command\CmdDelete());
	}
	
	/**
	 * @return Command\ICmdDirect New direct object.
	 */
	public function direct() 
	{
		return $this->initialize(new Impl\Command\CmdDirect());
	}
	
	/**
	 * @return Command\ICmdInsert
	 */
	public function insert() 
	{
		return $this->initialize(new Impl\Command\CmdInsert());
	}
	
	/**
	 * @return Command\ICmdLock
	 */
	public function lock() 
	{ 
		return $this->initialize(new Impl\Command\CmdLock()); 
	}
	
	/**
	 * @return Command\ICmdSelect
	 */
	public function select() 
	{
		return $this->initialize(new Impl\Command\CmdSelect());
	}
	
	/**
	 * @return Command\ICmdUpdate
	 */
	public function update()
	{
		return $this->initialize(new Impl\Command\CmdUpdate());
	}
	
	/**
	 * @return Command\ICmdUpsert
	 */
	public function upsert()
	{
		return $this->initialize(new Impl\Command\CmdUpsert());
	}
	
	/**
	 * @return Command\ICmdDB
	 */
	public function db()
	{
		return $this->initialize(new Impl\Command\CmdDB());
	}
	
	/**
	 * @return Command\ICmdCreate
	 */
	public function create()
	{
		return $this->initialize(new Impl\Command\CmdCreate());
	}
	
	/**
	 * @return Command\ICmdMultiQuery
	 */
	public function bulk()
	{
		return $this->initialize(new Impl\Command\CmdMultiQuery());
	}
	
	public function close()
	{
		$this->connection->close();
	}
	
	/**
	 * @return string
	 */
	public function name()
	{
		return $this->connectionName;
	}
}