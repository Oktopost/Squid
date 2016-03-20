<?php
namespace Squid\Base;


require_once 'Oktopost/MySql/Interfaces/IMySqlConn.php';


/**
 * Handle all errors returned by query execution or connection.
 * Classes with this interface should work similar to Decorator Class.
 * See Decorator Design Pattern.
 */
interface IMySqlSafeConn extends IMySqlConn {
	
	/**
	 * Initialize the object with connection and reporter it should use.
	 * @param IMySqlConn $conn Connection to use.
	 */
	public function init(IMySqlConn $conn);
	
	/**
	 * Set the used error reporter.
	 * @param IErrorReporter $reporter Reporter to use for error handling.
	 */
	public function setReporter(IErrorReporter $reporter);
	
}