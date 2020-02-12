<?php
namespace Squid\MySql\Connectors\Objects\Join\OneToOne;


use Squid\MySql\Connectors\Objects\IIdentityConnector;
use Squid\MySql\Connectors\Objects\Generic\IGenericIdentityConnector;


interface IOneToOneIdentityConnector extends 
	IOneToOneConnector,
	IIdentityConnector,
	IGenericIdentityConnector
{

}