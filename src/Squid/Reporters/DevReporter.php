<?php
namespace Squid\Reporters;


use \Squid\Base\IErrorReporter;


/**
 * Reporter that should not be used in production
 */
class DevReporter implements IErrorReporter {
	
	/**
	 * @param \Exception $e
	 * @param string $cmd Command that was executed. Empty on connection error.
	 * @param array $bind Command bind params. Empty on connection error.
	 */
	public function reportException(\Exception $e, $cmd = '', $bind = array()) {
		// TODO: Implement reportException() method.
	}
	
	/**
	 * @todo fix type
	 * @param mixed $result Returned result.
	 * @param string $cmd Command that was executed.
	 * @param array $bind Command bind params.
	 */
	public function reportFailedQuery($result, $cmd = '', $bind = array()) {
		
	}
}