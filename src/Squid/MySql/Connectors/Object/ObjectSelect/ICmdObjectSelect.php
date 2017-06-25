<?php
namespace Squid\MySql\Connectors\Object\ObjectSelect;


use Squid\MySql\Command\ISelect;


interface ICmdObjectSelect extends IObjectQuery, ISelect
{
	public function __clone();
	public function __toString();
}