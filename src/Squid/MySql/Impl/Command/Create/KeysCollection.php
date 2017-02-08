<?php
namespace Squid\MySql\Impl\Command\Create;


use Squid\Exceptions\SquidException;


class KeysCollection
{
	private $primary = null;
	private $indexes = [];
	private $foreign = [];
	
	
	/**
	 * @param string|null $name
	 * @return ForeignKey
	 */
	public function foreignKey($name = null) 
	{
		$key = new ForeignKey();
		$this->foreign[] = $key;
		
		if ($name) 
			$key->name($name);
		
		return $key;
	}
	
	/**
	 * @param string $name
	 * @param string[] ...$columns
	 */
	public function index($name, ...$columns)
	{
		$this->indexes[] = 
			"INDEX $name (`" . implode('`,`', $columns) . '`)';
	}
	
	/**
	 * @param string $name
	 * @param string[] ...$columns
	 */
	public function unique($name, ...$columns)
	{
		$this->indexes[] = 
			"UNIQUE $name (`" . implode('`,`', $columns) . '`)';
	}
	
	/**
	 * @param string[] ...$columns
	 */
	public function primary(...$columns)
	{
		if ($this->primary)
			throw new SquidException('Primary key can not be redefined');
		
		$this->primary = $columns;
	}
	
	
	/**
	 * @return string
	 */
	public function build()
	{
		
	}
}