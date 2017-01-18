<?php
namespace Squid\MySql\Impl\Command\Create;


use Squid\MySql\Command\Create\IColumn;


interface IColumnsTarget
{
	public function add(IColumn $column);
}