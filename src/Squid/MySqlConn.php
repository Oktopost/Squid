<?php
namespace Squid;


use \Squid\Base\IMySqlConn;


/**
 * Default connector implementation.
 */
class MySqlConn implements IMySqlConn {
	
	
	/**
	 * @param string $db
	 * @param string $user
	 * @param string $pass
	 * @param string $host
	 */
	public function connect($db, $user, $pass, $host) {
		/**
		 * @todo Implement
		 */
	}
	
	/**
	 * Close any opened connection. If connection is not open, do nothing.
	 */
	public function close() {
		/**
		 * @todo Implement
		 */
	}
	
	/**
	 * @param string $cmd Sql SAFE query to execute.
	 * @param array $bind Array of parameters to bind.
	 * @return mixed
	 */
	public function execute($cmd, $bind) {
		/**
		 * @todo Implement
		 */
	}
}