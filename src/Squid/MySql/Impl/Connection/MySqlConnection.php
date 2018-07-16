<?php
namespace Squid\MySql\Impl\Connection;


use Squid\MySql\Config\MySqlConnectionConfig;
use Squid\MySql\Exceptions\MySqlException;
use Squid\MySql\Connection\IMySqlConnection;

use Squid\Exceptions\SquidException;


class MySqlConnection implements IMySqlConnection 
{
	/** @var MySqlConnectionConfig */
	private $config = null;
	
	/** @var \PDO */
	private $pdo = null;
	
	
	private function openConnection() 
	{
		try
		{
			$this->pdo = new \PDO(
				$this->config->getPDOConnectionString(),
				$this->config->User,
				$this->config->Pass);
		}
		catch (\PDOException $e)
		{
			throw MySqlException::create($e);
		}
		
		foreach ($this->config->PDOFlags as $flag => $value)
		{
			$this->pdo->setAttribute($flag, $value);
		}
	}
	
	/**
	 * @param \PDOStatement $statement
	 * @param array $params
	 */
	private function bindParams(\PDOStatement $statement, array $params)
	{
		foreach ($params as $index => $value)
		{
			$value = $params[$index];
			
			if (is_array($value)) 
			{
				if (count($value) != 2)
					throw new SquidException('Invalid bind value: ' . json_encode($value));
				
				$statement->bindValue($index + 1, $value[0], $value[1]);
			}
			else 
			{
				$statement->bindValue($index + 1, $value);
			}
		}
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

	public function version(): string
	{
		return $this->config->Version;
	}

	/**
	 * @param string $cmd Sql SAFE query to execute.
	 * @param array $bind Array of parameters to bind.
	 * @throws \PDOException
	 * @return \PDOStatement
	 */
	public function execute($cmd, array $bind = []) 
	{
		if (is_null($this->pdo)) 
			$this->openConnection();
		
		$statement = $this->pdo->prepare($cmd);
		
		if (!$statement) 
			throw MySqlException::create($this->pdo->errorInfo());
		
		$this->bindParams($statement, $bind);
		$result = $statement->execute();
		
		if (!$result)
			throw MySqlException::create($statement->errorInfo());
		
		return $statement;
	}
}