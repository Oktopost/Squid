<?php
namespace Squid\Base\Cmd;


use \Squid\Base\ICmdCreator;


/**
 * Create a delete from a single table
 */
interface ICmdDelete extends IDml, ICmdCreator, IWithWhere, IWithLimit 
{	
	/**
	 * @param string $table
	 * @return static
	 */
	public function from($table);
}