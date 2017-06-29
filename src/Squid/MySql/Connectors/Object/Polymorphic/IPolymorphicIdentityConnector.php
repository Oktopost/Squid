<?php
namespace Squid\MySql\Connectors\Object\Polymorphic;


use Squid\MySql\Connectors\Object\IIdentityConnector;


interface IPolymorphicIdentityConnector extends 
	IPolymorphicConnector,
	IIdentityConnector
{
	
}