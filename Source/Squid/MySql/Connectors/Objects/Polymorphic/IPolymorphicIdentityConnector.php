<?php
namespace Squid\MySql\Connectors\Objects\Polymorphic;


use Squid\MySql\Connectors\Objects\IIdentityConnector;


interface IPolymorphicIdentityConnector extends 
	IPolymorphicConnector,
	IIdentityConnector
{
	
}