<?php
namespace Squid\MySql\Connectors\Object;


use Squid\MySql\Connectors\Object\CRUD\ID\IIdSave;
use Squid\MySql\Connectors\Object\CRUD\ID\IIdLoad;
use Squid\MySql\Connectors\Object\CRUD\ID\IIdDelete;


/**
 * Refers to an object that have a single column as it's Primary Key.
 */
interface IIdConnector extends 
	IIdentityConnector,
	IIdDelete,
	IIdLoad,
	IIdSave
{
	
}