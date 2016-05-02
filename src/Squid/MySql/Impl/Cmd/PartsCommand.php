<?php
namespace Squid\MySql\Impl\Cmd;


use Squid\Common;


/**
 * Abstraction for a command class that is build using different parts.
 * Clone can be applied on this object.
 * Note that part does not have to be an array as long as appendPart is not used 
 * for this specific part. 
 */
abstract class PartsCommand extends AbstractCommand {
	
	/**
	 * @var array Array of all parts.
	 */
	private $parts	= array();
	
	/**
	 * @var array Array of bind params for each part.
	 */
	private $bind	= array();
	
	
	public function __construct() {
		$this->parts= $this->getDefaultParts();
		$this->bind	= $this->parts;
	}
	
	
	/**
	 * Get the bind parameters.
	 * @return array Array of bind params.
	 */
	public function bind() {
		$bindParams = array();
		
		// NOTE: array_reduce is much slower function, so it's not used.
		foreach ($this->bind as $partParams) {
			if ($partParams) {
				$bindParams = array_merge($bindParams, $partParams);
			}
		}
		
		return $bindParams;
	}
	
	/**
	 * Generate the query string.
	 * @return string Currently set query.
	 */
	public function assemble() {
		return $this->generate();
	}

	public function __toString()
	{
		return "[{$this->assemble()}] : [" . json_encode($this->bind()) . "]";
	}


	/**
	 * Get the parts this query can have.
	 * @return array Array contianing only the part as keys and values set to false.
	 */
	protected abstract function getDefaultParts();
	
	/**
	 * Commbine all the parts into one sql.
	 * @return string Created query.
	 */
	protected abstract function generate();
	
	
	/**
	 * Append new query to given part.
	 * @param int $part Part to append to.
	 * @param string $sql Command to append.
	 * @param array|bool $bind Bind params.
	 * @return mixed Always returns self.
	 */
	protected function appendPart($part, $sql, $bind = false) {
		Common::toArray($sql);
		
		if (!$this->parts[$part]) {
			$this->parts[$part]	= $sql;
		} else {
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
	 * @return mixed Always returns self.
	 */
	protected function setPart($part, $sql, $bind = false) {
		$this->parts[$part] = $sql;
		
		if (is_array($bind)) {
			$this->bind[$part] = $bind;
		} else if ($bind === false) {
			$this->bind[$part] = false;
		} else {
			$this->bind[$part] = array($bind);
		}
		
		return $this;
	}
	
	/**
	 * Get the values stored in given part.
	 * @param int $part Part to return.
	 * @return mixed Value stored in this part.
	 */
	protected function getPart($part) {
		return $this->parts[$part];
	}
	
	/**
	 * Get the bind values for given aprt.
	 * @param int $part Part to the the bind values for.
	 */
	protected function getBind($part) {
		return $this->bind[$part];
	}
	
	
	/**
	 * Append bind values to given part.
	 * @param int $part Part to appenf to.
	 * @param mixed|array|false $bind Single value to append, false to ignore, array of bind 
	 * values or array of arrays of bind values.
	 * @return mixed Always returns self.
	 */
	private function appendBind($part, $bind) {
		if ($bind === false) {
			return $this;
		}
		
		Common::toArray($bind);
		
		if (!$this->bind[$part]) {
			$this->bind[$part] = $bind;
		} else {
			$this->bind[$part] = array_merge($this->bind[$part], $bind);
		}
		
		return $this;
	}
}