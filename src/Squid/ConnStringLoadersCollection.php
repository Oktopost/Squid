<?php
namespace Squid;


use \Squid\Base\IConnStringLoader;


class ConnStringLoadersCollection implements IConnStringLoader {
	
	private $collections;
	
	
	public function __construct() {
		$this->collections = array(new DefaultConnStringLoader());
	}
	
	
	/**
	 * @param IConnStringLoader $loader
	 */
	public function add(IConnStringLoader $loader) {
		$this->collections[] = $loader;
	}
	
	
	/**
	 * @param string $connName
	 * @return array
	 */
	public function getConnString($connName) {
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
	public function hasConnString($connName) {
		foreach ($this->collections as $loader) {
			if ($loader->hasConnString($connName)) {
				return true;
			}
		}
		
		return false;
	}
}