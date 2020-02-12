<?php
namespace Squid\MySql\Connectors\Objects\Query;


use Squid\MySql\Command\ISelect;


interface ICmdObjectSelect extends IObjectQuery, ISelect
{
	public function __clone();
	public function __toString();
}