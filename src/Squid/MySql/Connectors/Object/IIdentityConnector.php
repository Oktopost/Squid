<?php
namespace Squid\MySql\Connectors\Object;


use Squid\MySql\Connectors\Object\CRUD\Identity\IIdentityDelete;
use Squid\MySql\Connectors\Object\CRUD\Generic\IObjectInsert;
use Squid\MySql\Connectors\Object\CRUD\Identity\IIdentityUpdate;
use Squid\MySql\Connectors\Object\CRUD\Identity\IIdentityUpsert;


/**
 * Refers to an object that have a Primary Key identifier, ether a single column or a combined index.
 */
interface IIdentityConnector extends 
	IObjectInsert,
	IIdentityDelete,
	IIdentityUpdate,
	IIdentityUpsert
{
	
}