<?php
namespace Squid\Base\Cmd;


use \Squid\Base\ICmdCreator;


/**
 * Used to execute complex queries
 */
interface ICmdDirect extends IQuery, IDml, ICmdCreator 
{	
	/**
	 * @param string $sql Sql command to execute (Must be safe!!!)
	 * @param array $bind
	 * @return static
	 */
	public function command($sql, array $bind = array());
}