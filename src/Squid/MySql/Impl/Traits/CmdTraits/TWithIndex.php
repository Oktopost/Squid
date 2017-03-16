<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\MySql\Command\Create\IForeignKey;
use Squid\MySql\Impl\Command\Create\KeysCollection;


trait TWithIndex
{
	/** @var KeysCollection */
	private $indexes;
	
	
	/**
	 * @param string[] ...$columns
	 * @return static
	 */
	public function primary(...$columns)
	{
		$this->indexes->primary(...$columns);
		return $this;
	}
	
	/**
	 * @param string|string[] $name
	 * @param string[] $columns
	 * @return static
	 */
	public function index($name, array $columns = [])
	{
		$this->indexes->index($name, $columns);
		return $this;
	}
	
	/**
	 * @param string|string[] $name
	 * @param string[] $columns
	 * @return static
	 */
	public function unique($name, array $columns = [])
	{
		$this->indexes->unique($name, $columns);
		return $this;
	}
	
	/**
	 * @param string|null $name
	 * @return IForeignKey
	 */
	public function foreignKey($name = null)
	{
		return $this->indexes->foreignKey($name);
	}
}