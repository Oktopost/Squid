<?php
namespace Squid\MySql\Impl\Command;



abstract class PartsCommand extends AbstractCommand
{
	/** @var array */
	private $parts	= [];
	
	/** @var array */
	private $bind	= [];
	
	
	/**
	 * Append bind values to given part.
	 * @param int $part Part to append to.
	 * @param mixed|array|false $bind
	 * @return static
	 */
	private function appendBind($part, $bind) 
	{
		if ($bind === false) return $this;
		
		if (!is_array($bind)) $bind = [$bind];
		
		if (!$this->bind[$part]) 
		{
			$this->bind[$part] = $bind;
		}
		else 
		{
			$this->bind[$part] = array_merge($this->bind[$part], $bind);
		}
		
		return $this;
	}
	
	/**
	 * @param array $finalParams
	 * @param array $singlePartParams
	 * @return array
	 */
	private function combineBindParams($finalParams, $singlePartParams)
	{
		foreach ($singlePartParams as &$param)
		{
			if ($param instanceof \DateTime)
			{
				$param = $param->format('Y-m-d H:i:s');
			}
		}
		
		return array_merge($finalParams, $singlePartParams);
	}
	
	
	/**
	 * Append new query to given part.
	 * @param int $part Part to append to.
	 * @param string $sql Command to append.
	 * @param array|bool $bind Bind params.
	 * @return static
	 */
	protected function appendPart($part, $sql, $bind = false) 
	{
		if (!is_array($sql)) $sql = [$sql];
		
		if (!$this->parts[$part]) 
		{
			$this->parts[$part] = $sql;
		}
		else
		{
			$this->parts[$part] = array_merge($this->parts[$part], $sql);
		}
		
		return $this->appendBind($part, $bind);
	}
	
	/**
	 * Override part value.
	 * @param int $part Part to set.
	 * @param string|array $sql Value to set for this part. Use only for parts that will not
	 * be appended to. Otherwise pass as array.
	 * @param array|bool $bind Array of bind values if any.
	 * @return static
	 */
	protected function setPart($part, $sql, $bind = false)
	{
		$this->parts[$part] = $sql;
		
		if (is_array($bind)) 		$this->bind[$part] = $bind;
		else if ($bind === false)	$this->bind[$part] = false;
		else						$this->bind[$part] = [$bind];
		
		return $this;
	}
	
	/**
	 * @param int $part Part to return.
	 * @return mixed Value stored in this part.
	 */
	protected function getPart($part) 
	{
		return $this->parts[$part];
	}
	
	/**
	 * @param int $part Part to the the bind values for.
	 * @return mixed
	 */
	protected function getBind($part)
	{
		return $this->bind[$part];
	}
	
	/**
	 * Get the parts this query can have.
	 * @return array Array containing only the part as keys and values set to false.
	 */
	protected abstract function getDefaultParts();
	
	/**
	 * Combine all the parts into one sql.
	 * @return string Sql query
	 */
	protected abstract function generate();
	
	
	public function __construct() 
	{
		$this->parts= $this->getDefaultParts();
		$this->bind	= $this->parts;
	}
	
	
	/**
	 * @return array
	 */
	public function bind() 
	{
		$bindParams = [];
		
		foreach ($this->bind as $partParams) 
		{
			if ($partParams) 
			{
				$bindParams = $this->combineBindParams($bindParams, $partParams);
			}
		}
		
		return $bindParams;
	}
	
	/**
	 * @return string 
	 */
	public function assemble() 
	{
		return $this->generate();
	}
}