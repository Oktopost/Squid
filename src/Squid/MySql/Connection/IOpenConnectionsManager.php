<?php
namespace Squid\MySql\Connection;


interface IOpenConnectionsManager 
{
	/**
	 * @param IMySqlConnection $connection
	 */
	public function subscribeToClose(IMySqlConnection $connection);
}