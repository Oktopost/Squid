<?php
namespace Squid\MySql\Connectors\Object\Join\OneToOne;


use Squid\MySql\Connectors\Object\IIdentityConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericIdentityConnector;


interface IOneToOneIdentityConnector extends 
	IOneToOneConnector,
	IIdentityConnector,
	IGenericIdentityConnector
{

}