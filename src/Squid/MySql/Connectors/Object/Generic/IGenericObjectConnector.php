<?php
namespace Squid\MySql\Connectors\Object\Generic;


use Squid\MySql\Connectors\Generic\ICountConnector;
use Squid\MySql\Connectors\Generic\IDeleteConnector;
use Squid\MySql\Connectors\Generic\IUpdateConnector;
use Squid\MySql\Connectors\Generic\IUpsertConnector;

use Squid\MySql\Connectors\Object\IObjectConnector;


interface IGenericObjectConnector extends 
	IObjectConnector,
	
	IDeleteConnector,
	IUpdateConnector,
	ICountConnector,
	IUpsertConnector
{

}