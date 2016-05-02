<?php
namespace Squid\MySql\Connection;


use Squid\MySql\Config\MySqlConnectionConfig;

interface IMySqlConnection 
{
	/**
	 * @param MySqlConnectionConfig|string $db
	 * @param string $user
	 * @param string $pass
	 * @param string $host
	 */
	public function connect($db, $user = null, $pass = null, $host = null);
	
	/**
	 * When creating a new connection that is not managed by the IConnections object, 
	 * it's possible to subscribe to a manager that is used to close all open connections.
	 * Any connection not subscribe to manager and not managed by IConnections will not be 
	 * closed when IConnections->closeAll() is called. 
	 * @param IOpenConnectionsManager $manager
	 */
	public function setOpenConnectionsManager(IOpenConnectionsManager $manager);
	
	/**
	 * Close this connection. Do nothing if the connection is already closed.
	 */
	public function close();
	
	/**
	 * @return bool
	 */
	public function isOpen();
	
	/**
	 * @param string $cmd
	 * @param array $bind
	 * @return \mixed
	 */
	public function execute($cmd, array $bind = []);
}