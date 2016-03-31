<?php
namespace Squid\Base\Cmd;


use \Squid\Base\ICmdCreator;


/**
 * Used to execute complex queries
 */
interface ICmdDirect extends IQuery, IDml, ICmdCreator {
	
	/**
	 * Set the query to execute.
	 * @param string $sql Sql command to execute.
	 * @param array $bind Array of bind params.
	 * @return static
	 */
	public function command($sql, array $bind = array());
	
}