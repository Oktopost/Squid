<?php
namespace Squid\MySql\Connectors\Object\ObjectSelect;


interface IQueryConnector
{
	public function query(): ICmdObjectSelect;
}