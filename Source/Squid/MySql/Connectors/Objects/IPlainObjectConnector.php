<?php
namespace Squid\MySql\Connectors\Objects;


use Squid\MySql\Connectors\Objects\CRUD\Generic;


interface IPlainObjectConnector extends 
	Generic\IObjectInsert,
	Generic\IObjectSelect,
	Generic\IObjectUpdate,
	Generic\IObjectUpsert
{
	
}