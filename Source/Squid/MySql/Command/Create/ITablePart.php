<?php
namespace Squid\MySql\Command\Create;


interface ITablePart
{
	/**
	 * @return string
	 */
	public function assemble();
}