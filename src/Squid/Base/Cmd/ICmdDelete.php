<?php
namespace Squid\Base\Cmd;


use \Squid\Base\IMySqlCommand;


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