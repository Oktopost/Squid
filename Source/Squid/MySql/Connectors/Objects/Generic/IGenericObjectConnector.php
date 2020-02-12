<?php
namespace Squid\MySql\Connectors\Objects\Generic;


use Squid\MySql\Connectors\Generic\ICountConnector;
use Squid\MySql\Connectors\Generic\IDeleteConnector;

use Squid\MySql\Connectors\Objects\IPlainObjectConnector;


interface IGenericObjectConnector extends 
	IPlainObjectConnector,
	
	ICountConnector,
	IDeleteConnector
{

}