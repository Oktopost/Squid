<?php
namespace Squid\MySql\Command;


interface ICmdDelete extends IDml, IMySqlCommandConstructor, IWithWhere, IWithLimit 
{	
	/**
	 * @param string $table
	 * @return static
	 */
	public function from($table);
}