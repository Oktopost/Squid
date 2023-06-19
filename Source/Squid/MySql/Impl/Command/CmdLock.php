<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\ICmdLock;
use Squid\Exceptions\SquidException;


class CmdLock extends AbstractCommand implements ICmdLock 
{
	private $sql;
	
	/** @var array */
	private $params;
	
	
	private function get_safe_key(string $key): string
	{
		if (strlen($key) > 64)
			$key = md5($key);
		
		return $key;
	}
	
	
	/**
	 * @return array
	 */
	public function bind(): array 
	{
		return $this->params;
	}
	
	/**
	 * @return string
	 */
	public function assemble(): string 
	{
		return $this->sql;
	}
	
	
	/**
	 * @inheritdoc
	 * @throws SquidException
	 */
	public function lock($key, $timeout = 5) 
	{
		if (!is_int($timeout) || $timeout < 0 || $timeout > 40) 
			throw new SquidException("Invalid value for timeout '$timeout'");
		
		$key = $this->get_safe_key($key);
		
		$this->sql = 'SELECT GET_LOCK(?, ?)';
		$this->params = array($key, $timeout);
		
		$result = parent::execute();
		
		if (!$result || $result->errorCode() != '00000') return false;
		
		$row = $result->fetch(\PDO::FETCH_NUM);
		return ((int)$row[0] == 1);
	}
	
	/**
	 * @inheritdoc
	 */
	public function unlock($key) 
	{
		$key = $this->get_safe_key($key);
		
		$this->sql = 'DO RELEASE_LOCK(?)';
		$this->params = array($key);
		parent::execute();
	}
	
	/**
	 * @inheritdoc
	 */
	public function safe($callback, $key, $timeout = 5) 
	{
		if (!$this->lock($key, $timeout)) return false;
		
		try 
		{
			return $callback();
		}
		finally
		{
			$this->unlock($key);
		}
	}
	
	/**
	 * @inheritdoc
	 * @throws SquidException
	 */
	public function execute() 
	{
		throw new SquidException('Use lock or unlock methods for this command!');
	}
}