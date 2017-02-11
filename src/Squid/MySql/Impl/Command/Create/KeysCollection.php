<?php
namespace Squid\MySql\Impl\Command\Create;


use Squid\Exceptions\SquidException;


class KeysCollection
{
	private $primary = null;
	private $indexes = [];
	
	/** @var ForeignKey[] */
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
	 * @return array
	 */
	public function assemble()
	{
		$result = [];
		
		// Add primary.
		if ($this->primary)
		{
			$result = ['PRIMARY KEY (`' . implode('`, `', $this->primary) . '`)'];
		}
		
		// Add keys.
		$result = array_merge($result, $this->indexes);
		
		// Add foreign indexes.
		foreach ($this->foreign as $item) 
		{
			$result[] = $item->assemble();
		}
		
		return $result;
	}
}