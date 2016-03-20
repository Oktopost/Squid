<?php
namespace Squid\Base;


interface IMySqlConn {
	
	/**
	 * @param string $db
	 * @param string $user
	 * @param string $pass
	 * @param string $host
	 */
	public function connect($db, $user, $pass, $host);
	
	/**
	 * Close any opened connection. If connection is not open, do nothing.
	 */
	public function close();
	
	/**
	 * @param string $cmd
	 * @param array $bind
	 * @return \mixed
	 */
	public function execute($cmd, $bind);
}