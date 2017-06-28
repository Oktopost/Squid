<?php
namespace Squid\MySql\Connectors\Object;


use Squid\MySql\Connectors\Object\Query\ICmdObjectSelect;


interface IQueryConnector
{
	public function query(): ICmdObjectSelect;
}