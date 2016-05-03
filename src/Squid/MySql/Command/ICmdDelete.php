<?php
namespace Squid\MySql\Command;


/**
 * Create a delete from a single table
 */
interface ICmdDelete extends IDml, IMySqlCommand, IWithWhere, IWithLimit 
{	
	/**
	 * @param string $table
	 * @return static
	 */
	public function from($table);
}