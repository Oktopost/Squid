<?php
namespace Squid\MySql\Connectors\Objects;


use Squid\MySql\Connectors\Objects\CRUD\Identity;


/**
 * Refers to an object that have a Primary Key identifier, ether a single column or a combined index.
 */
interface IIdentityConnector extends
	Identity\IIdentityDelete,
	Identity\IIdentityUpdate,
	Identity\IIdentityUpsert,
	Identity\IIdentityInsert
{
	
}