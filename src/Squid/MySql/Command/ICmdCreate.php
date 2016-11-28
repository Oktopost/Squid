<?php
namespace Squid\MySql\Command;


use Squid\MySql\Command\Create\IColumnFactory;
use Squid\MySql\Command\Create\IColumnsSource;


interface ICmdCreate extends IColumnsSource 
{
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
}