<?php
namespace Squid\KeyValue;


interface IKeyValueProvider
{
	/**
	 * @param string $key
	 * @param callable $callback
	 * @return string
	 */
	public function get($key, $callback);
}