<?php
namespace Squid\MySql\Connectors\Object\Join\OneToOne;


use Squid\MySql\Connectors\Object\IIdConnector;


interface IOneToOneIdConnector extends 
	IOneToOneIdentityConnector,
	IIdConnector
{

}