<?php
namespace Squid\MySql\Command;


use Squid\MySql\Command\Create\IForeignKey;
use Squid\MySql\Command\Create\IColumnFactory;
use Squid\MySql\Command\Create\IColumnsSource;


interface ICmdCreate extends IColumnsSource 
{
	/**
	 * @return string
	 */
	public function getName();
	
	
	/**
	 * @return static
	 */
	public function temporary();
	
	/**
	 * @param string $name
	 * @return static
	 */
	public function table($name);

	/**
	 * @param string $name
	 * @return IColumnFactory
	 */
	public function column($name);
	
	/**
	 * @param string[] ...$columns
	 * @return static
	 */
	public function primary(...$columns);
	
	/**
	 * @param string[] ...$columns
	 * @return static
	 */
	public function index(...$columns);
	
	/**
	 * @param string[] ...$columns
	 * @return static
	 */
	public function unique(...$columns);
	
	/**
	 * @param string|null $name
	 * @return IForeignKey
	 */
	public function foreignKey($name = null);
}