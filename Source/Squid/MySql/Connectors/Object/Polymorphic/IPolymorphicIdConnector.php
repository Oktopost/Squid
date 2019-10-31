<?php
namespace Squid\MySql\Connectors\Object\Polymorphic;


use Squid\MySql\Connectors\Object\Generic\IGenericIdConnector;


interface IPolymorphicIdConnector extends
	IGenericIdConnector,
	IPolymorphicIdentityConnector
{
	
}