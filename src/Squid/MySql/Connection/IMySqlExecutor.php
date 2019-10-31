<?php
namespace Squid\MySql\Connection;


interface IMySqlExecutor
{
	/**
	 * @param string $cmd
	 * @param array $bind
	 * @return \PDOStatement|mixed
	 */
	public function execute(string $cmd, array $bind = []);
}