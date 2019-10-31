<?php
namespace Squid\MySql\Command;


interface ICmdDirect extends IQuery, IDml, IMySqlCommandConstructor 
{	
	/**
	 * @param string $sql Sql command to execute (Must be safe!!!)
	 * @param array $bind
	 * @return static
	 */
	public function command($sql, array $bind = array());
}