<?php
namespace Squid\MySql\Connectors;


use Squid\MySql\IMySqlConnector;


interface IGenericConnector
{
	/**
	 * @param IMySqlConnector $connector
	 * @return IGenericConnector|static
	 */
	public function setConnector(IMySqlConnector $connector): IGenericConnector;
}