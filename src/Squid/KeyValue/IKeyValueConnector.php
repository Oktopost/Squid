<?php
namespace Squid\KeyValue;


interface IKeyValueConnector
{
	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key);
	
	/**
	 * @param string $key
	 * @return string|null
	 */
	public function get($key);
	
	/**
	 * @param string $key
	 * @param string $value
	 * @return bool
	 */
	public function set($key, $value);
	
	/**
	 * @param string $key
	 */
	public function delete($key);
}