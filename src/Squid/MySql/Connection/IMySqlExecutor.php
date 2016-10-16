<?php
namespace Squid\MySql\Connection;


interface IMySqlExecutor
{
	/**
	 * @param string $cmd
	 * @param array $bind
	 * @return mixed
	 */
	public function execute($cmd, array $bind = []);
}