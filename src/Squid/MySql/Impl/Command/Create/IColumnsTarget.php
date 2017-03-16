<?php
namespace Squid\MySql\Impl\Command\Create;


use Squid\MySql\Command\Create\ITablePart;


interface IColumnsTarget
{
	public function add(ITablePart $column);
}