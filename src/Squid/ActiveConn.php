<?php
namespace Squid;


use \Squid\Base\IMySqlConn;
use \Squid\Base\IConnStringLoader;


/**
 * Keep truck of the currently active and open connections.
 */
class ActiveConn {
	
	/**
	 * @var array Associative array, where key is connection name, and value is it's MySqlConn instance.
	 */
	private $all = array();
	
	/**
	 * @var IConnStringLoader Object used to get the connection string.
	 */
	private $configLoader;
	
	/**
	 * @var string Name of the active connection. 
	 */
	private $activeConn;
	
	
	/**
	 * Create a new object and set what connection loader to use.
	 */
	public function __construct(IConnStringLoader $loader) {
		$this->configLoader = $loader;
	}
	
	
	/**
	 * Get all connections.
	 * @return array Array of all connections.
	 */
	public function getAll() {
		return array_values($this->all);
	}
	
	/**
	 * Get connection by name.
	 * @param string|false $connName Name of the connection to get. Set to false to 
	 * get the active connection.
	 * @return IMySqlConn Connection.
	 */
	public function get($connName = false) {
		if (!$connName) {
			return $this->getActive();
		}
		
		if (!isset($this->all[$connName])) {
			$this->all[$connName] = $this->load($connName);
		}
		
		return $this->all[$connName];
	}
	
	/**
	 * Set the currenly active connecton.
	 * @param string $connName Name of the connection to use.
	 * @return IMySqlConn New connection.
	 */
	public function set($connName) {
		$this->activeConn = $connName;
		return $this->get($connName);
	}
	
	/**
	 * Remove the connection by name. If active connection, and array has only 2 connection,
	 * the second connection will be activated. 
	 * @param string|bool $connName Connection to remove. If false, remove the active connection.
	 * @param bool $close If true, also close the connection.
	 * @return IMySqlConn|bool New connection or false if no other connection exists.
	 */
	public function remove($connName = false, $close = true) {
		if ($connName == false) {
			if (!isset($this->activeConn)) {
				return false;
			}
			
			$connName = $this->activeConn;
		}
		
		if (!isset($this->all[$connName])) {
			return $this->getActive();
		}
		
		$this->unsetConnection($connName, $close);
		
		if ($connName == $this->activeConn) {
			$this->resetActiveConn();
		}
		
		return $this->getActive();
	}
	
	
	/**
	 * Unset existing connection and return it.
	 * @param string $connName Name of the connection to unset.
	 * @param bool $close If true, also close the connection.
	 * @return IMySqlConn The removed connection.
	 */
	private function unsetConnection($connName, $close) {
		$conn = $this->all[$connName];
		unset($this->all[$connName]);
		
		if ($close) {
			$conn->close();
		}
	}
	
	/**
	 * Reset the activeConn data member to point to an availbe connection.
	 */
	private function resetActiveConn() {
		if (count($this->all) != 1) {
			unset($this->activeConn);
			return;
		}
		
		reset($this->all);
		$this->activeConn = key($this->all);
	}
	
	/**
	 * Load connection by name.
	 * @param string $connName Name of the connection to load.
	 * @return IMySqlConn New connection.
	 */
	private function load($connName) {
		$config = $this->configLoader->getConnString($connName);
		$conn = new MySqlConn();
		
		$conn->connect($config['db'], $config['user'], $config['pass'], $config['host']);
		
		return $conn;
	}
	
	/**
	 * Get the active connection.
	 * @return IMySqlConn|bool Active connection or false if none.
	 */
	private function getActive() {
		if (!isset($this->activeConn)) {
			return false;
		}
		
		return $this->all[$this->activeConn];
	}
}