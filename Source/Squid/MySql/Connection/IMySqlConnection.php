<?php
namespace Squid\MySql\Connection;


use Squid\MySql\Config\MySqlConnectionConfig;


interface IMySqlConnection extends IMySqlExecutor
{
	/**
	 * @param MySqlConnectionConfig|string $db
	 * @param string $user
	 * @param string $pass
	 * @param string $host
	 * @param string|null $charset
	 */
	public function setConfig($db, $user = null, $pass = null, $host = null, ?string $charset = null);
	
	/**
	 * Close this connection. Do nothing if the connection is already closed.
	 */
	public function close();
	
	/**
	 * @return bool
	 */
	public function isOpen();
	
	public function version(): string;
	
	public function getProperty(string $key, string $default = ''): string;
	
	/**
	 * @param string $cmd
	 * @param array $bind
	 * @return \PDOStatement
	 */
	public function execute(string $cmd, array $bind = []);
}