<?php
namespace Squid\Base;


/**
 * Used to notify about errors accured during query execution.
 */
interface IErrorReporter {
	
	/**
	 * Report an exception that accured during connection or command execution.
	 * @param \Exception $e Exception that was cought.
	 * @param string $cmd Command that was executed. Leave empty for connection error.
	 * @param array $bind Command bind params. Leave empty for connection error.
	 */
	public function reportException(\Exception $e, $cmd = '', $bind = array());
	
	/**
	 * Report a failed statment.
	 * @todo fix type
	 * @param mixed $result Returned result.
	 * @param string $cmd Command that was executed.
	 * @param array $bind Command bind params.
	 */
	public function reportFailedQuery($result, $cmd = '', $bind = array());
}