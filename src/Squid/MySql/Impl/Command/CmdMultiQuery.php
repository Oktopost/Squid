<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Impl\Command\MultiQuery\StatementResult;
use Squid\MySql\Command\IMySqlCommand;
use Squid\MySql\Command\ICmdMultiQuery;
use Squid\MySql\Command\MultiQuery\IStatementResult;
use Squid\MySql\Exceptions\MySqlException;


class CmdMultiQuery extends AbstractCommand implements ICmdMultiQuery
{
	private $command = '';
	private $bind = [];
	
	
	/**
	 * @param string|IMySqlCommand|array $query
	 * @param array $bind
	 * @return ICmdMultiQuery
	 */
	public function add($query, array $bind = [])
	{
		if (is_array($query))
		{
			foreach ($query as $item)
			{
				$this->add($item);
			}
			
			return $this;
		}
		else if ($query instanceof IMySqlCommand)
		{
			$bind = ($bind ?: $query->bind());
			$query = $query->assemble() . ';';
		}
		
		$this->command .= "$query;";
		$this->bind = array_merge($this->bind, $bind);
		
		return $this;
	}
	
	/**
	 * @return IStatementResult|false Result of the last query returned
	 */
	public function executeAll()
	{
		foreach ($this->executeIterator() as $result) {}
		
		if (!isset($result))
		{
			return false;
		}
		
		return $result;
	}
	
	private function checkForError(\PDOStatement $statement)
	{
		if ($statement->errorCode() != '00000')
			MySqlException::create($statement->errorInfo());
	}
	
	/**
	 * Return an iterator to iterate over all result sets.
	 * @return \Generator|IStatementResult[]
	 */
	public function executeIterator()
	{
		$result = $this->execute();
		
		if (!$result) 
			throw new MySqlException('Could not execute multiset query!');
		
			while (true)
			{
				yield new StatementResult($result);
				
				if (!$result->nextRowset())
				{
					$this->checkForError($result);
					break;
				}
			}
	}
	
	/**
	 * @param callable $callback Called for each single query.
	 * The callback receives the IStatementResult.
	 */
	public function executeWithCallback(callable $callback)
	{
		foreach ($this->executeIterator() as $result)
		{
			$callbackResult = $callback($result);
			
			if ($callbackResult === false)
			{
				break;
			}
		}
	}
	
	/**
	 * @return array
	 */
	public function bind()
	{
		return $this->bind;
	}
	
	/**
	 * Generate the query string.
	 * @return string Currently set query.
	 */
	public function assemble()
	{
		return $this->command;
	}
}