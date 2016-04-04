<?php
namespace Squid;


use Squid\MySql\IConnectionLoader;


class ConnectionLoadersCollection implements IConnectionLoader {
	
	private $collections;
	
	
	public function __construct() {
		$this->collections = array(new DefaultConnectionLoader());
	}
	
	
	/**
	 * @param IConnectionLoader $loader
	 */
	public function add(IConnectionLoader $loader) {
		$this->collections[] = $loader;
	}
	
	
	/**
	 * @param string $connName
	 * @return array
	 */
	public function getConnectionConfig($connName) {
		foreach ($this->collections as $loader) {
			if ($loader->hasConnString($connName)) {
				return $loader->getConnString($connName);
			}
		}
	}
	
	/**
	 * @param string $connName
	 * @return bool
	 */
	public function hasConnectionConfig($connName) {
		foreach ($this->collections as $loader) {
			if ($loader->hasConnString($connName)) {
				return true;
			}
		}
		
		return false;
	}
}