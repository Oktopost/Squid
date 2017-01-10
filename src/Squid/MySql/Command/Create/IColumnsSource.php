<?php
namespace Squid\MySql\Command\Create;


interface IColumnsSource
{
	/**
	 * @param IColumn $column
	 */
	public function add(IColumn $column);
}