<?php
namespace Squid\MySql\Command;


interface ICmdDelete extends IDml, IMySqlCommandConstructor, IWithWhere, IWithLimit 
{	
	/**
	 * @param string $table
	 * @param bool $escape
	 * @return static
	 */
	public function from($table, bool $escape = true);
}