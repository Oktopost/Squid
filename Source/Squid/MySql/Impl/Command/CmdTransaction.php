<?php
namespace Squid\MySql\Impl\Command;


use Squid\Exceptions\SquidException;
use Squid\Exceptions\NotInTransactionException;
use Squid\Exceptions\AlreadyInTransactionException;

use Squid\MySql\Command\ICmdTransaction;
use Squid\MySql\Command\IMySqlCommandConstructor;
use Squid\MySql\Impl\Traits\TMysqlCommand;


class CmdTransaction implements ICmdTransaction
{
	use TMysqlCommand;
	
	
	private $isInTransaction = false;


	/**
	 * @param string $command
	 * @param array $bind
	 * @throws
	 * @return bool
	 */
	private function executeDirect(string $command, array $bind = []): bool
	{
		$cmd = new CmdDirect();
		$cmd->setConnection($this->connection());
		$cmd->command($command, $bind);
		return $cmd->executeDml(false);
	}
	
	
	private function executeCommandConstructorSafe(IMySqlCommandConstructor $cmd)
	{
		return $this->executeInTransaction(function ()
			use ($cmd)
			{
				return $cmd->execute(); 
			});
	}

	/**
	 * @param IMySqlCommandConstructor[] $cmd
	 * @return mixed
	 */
	private function executeCommandConstructorArraySafe(array $cmd)
	{
		return $this->executeInTransaction(function ()
			use ($cmd)
			{
				$result = false;
					
				foreach ($cmd as $operation)
				{
					$result = $operation->execute();
					
					if ($result === false) break;
				} 
				
				return $result;
			});
	}

	/**
	 * @param callable $callback
	 * @return mixed
	 */
	private function executeCallbackSafe(callable $callback)
	{
		try
		{
			$result = $callback();
			
			if ($result === false)
			{
				$this->rollback();
			}
			else
			{
				$this->commit();
			}
			
			return $result;
		}
		catch (\Throwable $e)
		{
			$this->rollback();
			throw $e;
		}
	}
	
	
	/**
	 * Start a new transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool
	 */
	public function startTransaction(): bool
	{
		if ($this->isInTransaction) 
			throw new AlreadyInTransactionException();
		
		$this->isInTransaction = $this->executeDirect('START TRANSACTION');
		return $this->isInTransaction;
	}

	/**
	 * Commit current transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool
	 */
	public function commit(): bool
	{
		if (!$this->isInTransaction) 
			throw new NotInTransactionException();
		
		$this->isInTransaction = false;
		return $this->executeDirect('COMMIT');
	}

	/**
	 * Rollback current transaction.
	 * @see https://dev.mysql.com/doc/refman/5.7/en/commit.html
	 * @return bool
	 */
	public function rollback(): bool
	{
		$this->isInTransaction = false;
		$this->executeDirect('ROLLBACK');
		return true;
	}

	/**
	 * Are we currently inside a transaction.
	 * @return bool
	 */
	public function isInTransaction(): bool
	{
		return $this->isInTransaction;
	}

	/**
	 * @param IMySqlCommandConstructor|IMySqlCommandConstructor[]|callable $operation
	 * @return mixed
	 */
	public function executeInTransaction($operation)
	{
		if ($operation instanceof IMySqlCommandConstructor)
		{
			return $this->executeCommandConstructorSafe($operation);
		}
		else if (is_array($operation))
		{
			return $this->executeCommandConstructorArraySafe($operation);
		}
		else if (is_callable($operation))
		{
			return $this->executeCallbackSafe($operation);
		}
		else
		{
			throw new SquidException('Operation must be an IMySqlCommandConstructor, ' .   
				'IMySqlCommandConstructor array or callable!');
		}
	}
	
	
	/**
	 * For debug only
	 * @return string Return string in format: "Query string : {json bind params}"
	 */
	public function __toString()
	{
		return 'Transaction Command [' . ($this->isInTransaction ? 'IN TRANSACTION' : 'NO TRANSACTION') . ']';  
	}
}