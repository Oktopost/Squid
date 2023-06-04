<?php
namespace Squid\MySql\Impl;


use Squid\MySql\Impl;
use Squid\MySql\Command;
use Squid\MySql\IMySqlConnector;
use Squid\MySql\Query\IQuery;
use Squid\MySql\Query\IQueryHandler;
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
	public function __construct($name, ?IMySqlConnection $connection = null)
	{
		$this->connectionName = $name;
		
		if ($connection)
			$this->connection = $connection;
	}
	
	
	/**
	 * @param string|IQueryHandler $queryHandler
	 * @return IQueryHandler
	 */
	public function query($queryHandler): IQueryHandler
	{
		if (is_string($queryHandler))
			$queryHandler = new $queryHandler;
		
		/** @var IQuery $query */
		$query = new Impl\Query\Query($queryHandler, $this->select()); 
		$queryHandler->setup($query);
		
		return $queryHandler;
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
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->initialize(new Impl\Command\CmdController());
	}
	
	/**
	 * @return Command\ICmdDelete
	 */
	public function delete() 
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->initialize(new Impl\Command\CmdDelete());
	}
	
	/**
	 * @param string|null $command Optional command to execute.
	 * @param array $bind Optional bind params.
	 * @return Command\ICmdDirect
	 */
	public function direct(?string $command = null, array $bind = [])
	{
		/** @var Impl\Command\CmdDirect $cmd */
		$cmd = $this->initialize(new Impl\Command\CmdDirect());
		return ($command ? $cmd->command($command, $bind) : $cmd);
	}
	
	/**
	 * @return Command\ICmdInsert
	 */
	public function insert() 
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->initialize(new Impl\Command\CmdInsert());
	}
	
	/**
	 * @return Command\ICmdLock
	 */
	public function lock() 
	{ 
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->initialize(new Impl\Command\CmdLock()); 
	}
	
	/**
	 * @return Command\ICmdSelect
	 */
	public function select() 
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->initialize(new Impl\Command\CmdSelect());
	}
	
	/**
	 * @return Command\ICmdUpdate
	 */
	public function update()
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->initialize(new Impl\Command\CmdUpdate());
	}
	
	/**
	 * @return Command\ICmdUpsert
	 */
	public function upsert()
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->initialize(new Impl\Command\CmdUpsert());
	}
	
	public function transaction(): Command\ICmdTransaction
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->initialize(new Impl\Command\CmdTransaction());
	}
	
	/**
	 * @return Command\ICmdDB
	 */
	public function db()
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->initialize(new Impl\Command\CmdDB());
	}
	
	/**
	 * @return Command\ICmdCreate
	 */
	public function create()
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $this->initialize(new Impl\Command\CmdCreate());
	}
	
	/**
	 * @return Command\ICmdMultiQuery
	 */
	public function bulk()
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
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