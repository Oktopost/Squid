<?php
namespace Squid\Base;


interface IConnStringLoader {
	
	/**
	 * @param string $connName
	 * @return array
	 */
	public function getConnString($connName);
	
	/**
	 * @param string $connName
	 * @return bool
	 */
	public function hasConnString($connName);
}