<?php
namespace Squid\MySql\Impl\Connection;


use Squid\MySql\Config\MySqlConnectionConfig;
use Squid\MySql\Exceptions\MySqlException;
use Squid\MySql\Connection\IMySqlConnection;

use Squid\Exceptions\SquidException;


class MySqlConnection implements IMySqlConnection 
{
	CONST MAX_RETRIES = 2;
	CONST RETRY_DELAY = 2000;
	
	
	/** @var MySqlConnectionConfig */
	private $config = null;
	
	/** @var \PDO */
	private $pdo = null;
	
	
	private function getMysqlBool($value): int
	{
		return $value ? 1 : 0;
	}
	
	private function openConnection(): void
	{
		$exceptions = [];
		
		for ($i = -1; $i < self::MAX_RETRIES; $i++)
		{
			try
			{
				$this->pdo = new \PDO(
					$this->config->getPDOConnectionString(),
					$this->config->User,
					$this->config->Pass);
				
				break;
			}
			catch (\PDOException $e)
			{
				$exceptions[] = $e;
				
				usleep(self::RETRY_DELAY);
			}
		}
		
		if (!$this->pdo)
		{
			throw MySqlException::create($exceptions);
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
	private function bindParams(\PDOStatement $statement, array $params): void
	{
		foreach ($params as $index => $value)
		{
			$value = $params[$index];
			
			if (is_array($value)) 
			{
				if (count($value) != 2)
					throw new SquidException('Invalid bind value: ' . jsonencode($value));
				
				if (is_bool($value[0]))
					$value[0] = $this->getMysqlBool($value[0]);
				
				$statement->bindValue($index + 1, $value[0], $value[1]);
			}
			else 
			{
				if (is_bool($value))
					$value = $this->getMysqlBool($value);
				
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
	public function setConfig($db, $user = null, $pass = null, $host = null): void
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
	
	public function close(): void
	{
		$this->pdo = null;
	}
	
	public function isOpen(): bool
	{
		return !is_null($this->pdo);
	}

	public function version(): string
	{
		return $this->config->Version;
	}
	
	public function getProperty(string $key, string $default = ''): string
	{
		return $this->config->Properties[$key] ?? $default;
	}
	
	/**
	 * @param string $cmd Sql SAFE query to execute.
	 * @param array $bind Array of parameters to bind.
	 * @throws \PDOException
	 * @return \PDOStatement
	 */
	public function execute(string $cmd, array $bind = []) 
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