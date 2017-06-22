<?php
namespace Squid\MySql\Connectors;


use Squid\MySql\IMySqlConnector;


interface IConnector
{
	/**
	 * @param IMySqlConnector $connector
	 * @return IConnector|static
	 */
	public function setConnector(IMySqlConnector $connector): IConnector;
}