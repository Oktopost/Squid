<?php
namespace Squid\MySql\Connectors\Objects\Polymorphic;


use Squid\MySql\Connectors\Objects\Generic\IGenericIdConnector;


interface IPolymorphicIdConnector extends
	IGenericIdConnector,
	IPolymorphicIdentityConnector
{
	
}