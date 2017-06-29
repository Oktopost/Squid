<?php
namespace Squid\MySql\Connectors\Object\Generic;


use Squid\MySql\Connectors\Generic\ICountConnector;
use Squid\MySql\Connectors\Generic\IDeleteConnector;
use Squid\MySql\Connectors\Generic\IInsertConnector;
use Squid\MySql\Connectors\Generic\ISelectConnector;
use Squid\MySql\Connectors\Generic\IUpdateConnector;
use Squid\MySql\Connectors\Generic\IUpsertConnector;

use Squid\MySql\Connectors\Object\IPlainObjectConnector;


interface IGenericObjectConnector extends 
	IPlainObjectConnector,
	
	ICountConnector,
	IDeleteConnector,
	IUpdateConnector,
	IUpsertConnector,
	ISelectConnector,
	IInsertConnector
{

}