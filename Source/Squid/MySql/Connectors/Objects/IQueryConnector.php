<?php
namespace Squid\MySql\Connectors\Objects;


use Squid\MySql\Connectors\Objects\Query\ICmdObjectSelect;


interface IQueryConnector
{
	public function query(): ICmdObjectSelect;
}