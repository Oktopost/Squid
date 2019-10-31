<?php
namespace Squid\MySql\Command\Create;


interface IColumnsSource
{
	/**
	 * @param string $name
	 * @return IColumnFactory
	 */
	public function column($name);
}