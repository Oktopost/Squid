<?php
namespace Squid;


use \Squid\Common;
use \Squid\Base\IConnStringLoader;


class DefaultConnStringLoader implements IConnStringLoader {
	
	/**
	 * @param string $connName
	 * @return array
	 */
	public function getConnString($connName) {
		if ($connName == 'main') {
			$mysqlConfig = Common::config()->mysql;
		} else {
			$mysqlConfig = Common::config()->$connName->mysql;
		}
		
		return array(
            'db'	=> $mysqlConfig->database,
            'user'	=> $mysqlConfig->user,
            'pass'	=> $mysqlConfig->password,
            'host'	=> $mysqlConfig->host
        );
	}
	
	/**
	 * @param string $connName
	 * @return bool
	 */
	public function hasConnString($connName) {
		if ($connName == 'main') {
			return true;
		}
		
		return isset(Common::config()->$connName->mysql);
	}
}