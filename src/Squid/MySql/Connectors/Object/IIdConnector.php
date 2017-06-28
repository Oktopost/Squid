<?php
namespace Squid\MySql\Connectors\Object;


use Squid\MySql\Connectors\Object\CRUD\ID\IIDLoad;
use Squid\MySql\Connectors\Object\CRUD\ID\IIDDelete;


/**
 * Refers to an object that have a single column as it's Primary Key.
 */
interface IIdConnector extends 
	IIdentityConnector,
	IIDDelete,
	IIDLoad
{
	
}