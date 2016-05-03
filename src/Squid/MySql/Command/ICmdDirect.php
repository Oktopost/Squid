<?php
namespace Squid\MySql\Command;


/**
 * Used to execute complex queries
 */
interface ICmdDirect extends IQuery, IDml, IMySqlCommand 
{	
	/**
	 * @param string $sql Sql command to execute (Must be safe!!!)
	 * @param array $bind
	 * @return static
	 */
	public function command($sql, array $bind = array());
}