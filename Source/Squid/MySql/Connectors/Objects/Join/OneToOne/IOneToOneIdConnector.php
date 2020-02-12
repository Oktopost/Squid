<?php
namespace Squid\MySql\Connectors\Objects\Join\OneToOne;


use Squid\MySql\Connectors\Objects\IIdConnector;
use Squid\MySql\Connectors\Objects\Generic\IGenericIdConnector;


interface IOneToOneIdConnector extends 
	IOneToOneIdentityConnector,
	IIdConnector,
	IGenericIdConnector
{

}