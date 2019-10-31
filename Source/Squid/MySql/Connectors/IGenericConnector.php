<?php
namespace Squid\MySql\Connectors;


use Squid\MySql\Connectors\Generic;


interface IGenericConnector extends 
	Generic\ICountConnector,
	Generic\IDeleteConnector,
	Generic\IInsertConnector,
	Generic\ISelectConnector,
	Generic\IUpdateConnector,
	Generic\IUpsertConnector
{
	
}