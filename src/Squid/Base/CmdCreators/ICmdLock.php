<?php
namespace Squid\Base\CmdCreators;


use \Squid\Base\ICmdCreator;


interface ICmdLock extends ICmdCreator {
	
	/**
	 * @param string $key
	 * @param int $timeout In seconds
	 * @return bool
	 */
	public function lock($key, $timeout = 5);
	
	/**
	 * @param string $key
	 */
	public function unlock($key);
	
	/**
	 * @param callable $callback
	 * @param string $key
	 * @param int $timeout In seconds
	 * @return bool
	 */
	public function safe($callback, $key, $timeout = 5);
}