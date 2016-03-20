<?php
namespace Squid\Base\CmdCreators;


use \Squid\Base\ICmdCreator;


/**
 * Create a delete statment. This does not support select 
 * from mulitplay tables.
 */
interface ICmdDelete extends IDml, ICmdCreator, IWithWhere, IWithLimit {
	
	/**
	 * @param string $table
	 * @return static
	 */
	public function from($table);
	
}