<?php
namespace Squid\MySql\Impl\Connection;


use Squid\MySql\Connection\IMySqlConnection;
use Squid\MySql\Config\MySqlConnectionConfig;


class MySqlConnection implements IMySqlConnection 
{
	/** @var MySqlConnectionConfig */
	private $config = null;
	
	/** @var \PDO */
	private $pdo = null;
	
	
	private function openConnection() 
	{
		$this->pdo = new \PDO(
			$this->config->getPDOConnectionString(), 
			$this->config->User,
			$this->config->Pass);
	}
	
	
	public function __destruct()
	{
		$this->close();
	}
	
	
	/**
	 * @param MySqlConnectionConfig|string $db
	 * @param string|null $user
	 * @param string|null $pass
	 * @param string|null $host
	 */
	public function setConfig($db, $user = null, $pass = null, $host = null)
	{
		if ($db instanceof MySqlConnectionConfig) 
		{
			$this->config = $db;
			return;
		}
		
		$this->config = new MySqlConnectionConfig();
		
		$this->config->DB	= $db;
		$this->config->User = $user;
		$this->config->Pass = $pass;
		$this->config->Host = $host;
	}
	
	/**
	 * Close any opened connection. If connection is not open, do nothing.
	 */
	public function close() 
	{
		$this->pdo = null;
	}
	
	/**
	 * @return bool
	 */
	public function isOpen() 
	{
		return !is_null($this->pdo);
	}
	
	/**
	 * @param string $cmd Sql SAFE query to execute.
	 * @param array $bind Array of parameters to bind.
	 * @throws \PDOException
	 * @return \PDOStatement|false
	 */
	public function execute($cmd, array $bind = []) 
	{
		if (is_null($this->pdo)) 
			$this->openConnection();
		
		$statement = $this->pdo->prepare($cmd);
		
		return ($statement->execute($bind) ? $statement : false);
	}
}