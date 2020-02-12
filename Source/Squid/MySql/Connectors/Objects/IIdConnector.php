<?php
namespace Squid\MySql\Connectors\Objects;


use Squid\MySql\Connectors\Objects\CRUD\ID\IIdSave;
use Squid\MySql\Connectors\Objects\CRUD\ID\IIdLoad;
use Squid\MySql\Connectors\Objects\CRUD\ID\IIdDelete;


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