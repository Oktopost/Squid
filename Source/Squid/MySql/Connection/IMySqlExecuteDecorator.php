<?php
namespace Squid\MySql\Connection;


interface IMySqlExecuteDecorator extends IMySqlExecutor
{
	/**
	 * @param IMySqlExecutor $child Decorated executor.
	 */
	public function init(IMySqlExecutor $child = null);
}