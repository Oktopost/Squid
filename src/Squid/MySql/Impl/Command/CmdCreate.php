<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\ICmdCreate;
use Squid\MySql\Command\Create\IColumn;
use Squid\MySql\Command\Create\IForeignKey;
use Squid\MySql\Command\Create\IColumnFactory;
use Squid\MySql\Impl\Command\Create\ColumnFactory;
use Squid\MySql\Impl\Command\Create\IColumnsTarget;
use Squid\MySql\Impl\Command\Create\KeysCollection;


class CmdCreate implements ICmdCreate, IColumnsTarget  
{
	/** @var KeysCollection */
	private $indexes; 
	
	
	public function __construct()
	{
		$this->indexes = new KeysCollection();
	}

	// CREATE TABLE `okt`.`sad` ( `a` INT NOT NULL ) ENGINE = InnoDB CHARSET=binary COMMENT = 'asdasd';
	
	
	public function add(IColumn $column)
	{
		// TODO: Implement add() method.
	}
	
	
	/**
	 * @return string
	 */
	public function getName()
	{
		// TODO: Implement getName() method.
	}
	
	/**
	 * @return static
	 */
	public function temporary()
	{
		// TODO: Implement temporary() method.
	}
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function table($name)
	{
		// TODO: Implement table() method.
	}
	
	/**
	 * @param string $name
	 * @return IColumnFactory
	 */
	public function column($name)
	{
		return new ColumnFactory($this, $name);
	}
	
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
	 * @param string|null $name
	 * @param string[] ...$columns
	 * @return static
	 */
	public function index($name, ...$columns)
	{
		$this->indexes->index($name, ...$columns);
		return $this;
	}
	
	/**
	 * @param string|null $name
	 * @param string[] ...$columns
	 * @return static
	 */
	public function unique($name, ...$columns)
	{
		$this->indexes->unique($name, ...$columns);
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

	/**
	 * @param string $comment
	 * @return static
	 */
	public function comment($comment)
	{
		return $this;
	}
}