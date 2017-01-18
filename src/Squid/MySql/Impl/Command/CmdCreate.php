<?php
namespace Squid\MySql\Impl\Command;


use Squid\MySql\Command\Create\IColumn;
use Squid\MySql\Command\ICmdCreate;
use Squid\MySql\Command\Create\IForeignKey;
use Squid\MySql\Command\Create\IColumnFactory;
use Squid\MySql\Impl\Command\Create\ColumnFactory;
use Squid\MySql\Impl\Command\Create\ForeignKey;
use Squid\MySql\Impl\Command\Create\IColumnsTarget;


class CmdCreate implements ICmdCreate, IColumnsTarget  
{
	
	
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
		// TODO: Implement primary() method.
	}
	
	/**
	 * @param string|null $name
	 * @param string[] ...$columns
	 * @return static
	 */
	public function index($name, ...$columns)
	{
		// TODO: Implement index() method.
	}
	
	/**
	 * @param string|null $name
	 * @param string[] ...$columns
	 * @return static
	 */
	public function unique($name, ...$columns)
	{
		// TODO: Implement unique() method.
	}
	
	/**
	 * @param string|null $name
	 * @return IForeignKey
	 */
	public function foreignKey($name = null)
	{
		$key = new ForeignKey();
		// TODO: This add key.
		
		if ($name)
			$key->name($name);
		
		return $key;
	}
}