<?php
namespace Squid\MySql\Connectors\Objects\Generic;


use Squid\MySql\Connectors\Objects\IIdConnector;


interface IGenericIdConnector extends 
	IGenericIdentityConnector,
	IIdConnector
{
	
}