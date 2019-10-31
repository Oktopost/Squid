<?php
namespace Squid\MySql\Connectors\Object\Join\OneToOne;


use Squid\MySql\Connectors\Object\IIdConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericIdConnector;


interface IOneToOneIdConnector extends 
	IOneToOneIdentityConnector,
	IIdConnector,
	IGenericIdConnector
{

}